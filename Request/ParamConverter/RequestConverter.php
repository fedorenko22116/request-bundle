<?php

namespace LSBProject\RequestBundle\Request\ParamConverter;

use LSBProject\RequestBundle\Configuration\ConfigurationInterface;
use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Util\CamelCaseConverterInterface;
use LSBProject\RequestBundle\Util\Factory\ParamConverterFactoryInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\ReflectionExtractorInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestConverter implements ParamConverterInterface
{
    /**
     * @var CamelCaseConverterInterface
     */
    private $camelCaseConverter;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ParamConverterManager
     */
    private $converterManager;

    /**
     * @var ReflectionExtractorInterface
     */
    private $reflectionExtractor;

    /**
     * @var ParamConverterFactoryInterface
     */
    private $paramConverterFactory;

    public function __construct(
        CamelCaseConverterInterface $camelCaseConverter,
        ValidatorInterface $validator,
        ParamConverterManager $converterManager,
        ReflectionExtractorInterface $reflectionExtractor,
        ParamConverterFactoryInterface $paramConverterFactory
    ) {
        $this->camelCaseConverter = $camelCaseConverter;
        $this->validator = $validator;
        $this->converterManager = $converterManager;
        $this->reflectionExtractor = $reflectionExtractor;
        $this->paramConverterFactory = $paramConverterFactory;
    }

    /**
     * {@inheritDoc}
     * @throws ReflectionException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $class = $configuration->getClass();
        $meta = new ReflectionClass($class);
        $props = $this->reflectionExtractor->extract($meta, $this->filterProps($meta, $request));

        /** @var AbstractRequest $object */
        $object = $meta->newInstance();

        foreach ($props as $prop) {
            $var = $prop->isBuiltInType() ?
                $request->get($this->camelCaseConverter->convert($prop->getName())) :
                $this->getFromConverters($prop, $request);

            if ($meta->hasMethod($method = 'set' . ucfirst($prop->getName()))) {
                $object->$method($var);
            } else {
                $object->{$prop->getName()} = $var;
            }
        }

        if (($result = $this->validator->validate($object))->count()) {
            throw new BadRequestHttpException($result->get(0)->getMessage());
        }

        if (!$object->validate()) {
            throw new BadRequestHttpException($object->getErrorMessage());
        }

        $request->attributes->add([$configuration->getName() => $object]);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration)
    {
        try {
            return (new ReflectionClass($configuration->getClass()))->isSubclassOf(AbstractRequest::class);
        } catch (ReflectionException $exception) {
            return false;
        }
    }

    /**
     * @param ReflectionClass $meta
     * @param Request $request
     * @return string[]
     */
    private function filterProps(ReflectionClass $meta, Request $request)
    {
        $props = array_filter(
            $meta->getProperties(),
            function (ReflectionProperty $prop) use ($request, $meta) {
                return $prop->isPublic() || $meta->hasMethod('set' . ucfirst($prop));
            }
        );

        return array_map(function (ReflectionProperty $property) {
            return $property->getName();
        }, $props);
    }

    /**
     * @param ConfigurationInterface $configuration
     * @param Request $request
     * @return object|null
     */
    private function getFromConverters(ConfigurationInterface $configuration, Request $request)
    {
        $options = $configuration->getOptions();
        $id = isset($options['id']) ? $options['id'] : $request->get('id');

        if ($id) {
            $request->attributes->set($id, $request->get($this->camelCaseConverter->convert($id)));
        }

        /** @var array<string, string> $mapping */
        $mapping = isset($options['mapping']) ? $options['mapping'] : [];
        $meta = isset($options['meta']) ? $options['meta'] : [];

        unset($options['meta']);

        $mapping = array_merge($mapping, $meta);

        foreach ($mapping as $alias => $option) {
            if ($option === "expr") {
                continue;
            }

            $request->attributes->set($alias, $request->get($this->camelCaseConverter->convert($alias)));
        }

        $paramConfig = $this->paramConverterFactory->create($configuration);
        $this->converterManager->apply($request, $paramConfig);
        $var = $request->attributes->get($configuration->getName());
        $request->attributes->remove($configuration->getName());

        if ($id) {
            $request->attributes->remove($id);
        }

        foreach ($mapping as $alias => $option) {
            $request->attributes->remove($alias);
        }

        return $var;
    }
}
