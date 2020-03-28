<?php

namespace LSBProject\RequestBundle\Request\Factory;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Request\Validator\RequestValidatorInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use LSBProject\RequestBundle\Util\ReflectionExtractor\ReflectionExtractorInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RequestFactory implements RequestFactoryInterface
{
    /**
     * @var ReflectionExtractorInterface
     */
    private $reflectionExtractor;

    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var RequestValidatorInterface
     */
    private $validator;

    /**
     * @param ReflectionExtractorInterface $reflectionExtractor
     * @param RequestManagerInterface      $requestManager
     * @param RequestValidatorInterface    $validator
     */
    public function __construct(
        ReflectionExtractorInterface $reflectionExtractor,
        RequestManagerInterface $requestManager,
        RequestValidatorInterface $validator
    ) {
        $this->reflectionExtractor = $reflectionExtractor;
        $this->requestManager = $requestManager;
        $this->validator = $validator;
    }

    /**
     * {@inheritDoc}
     * @throws ReflectionException
     * @throws UnprocessableEntityHttpException
     */
    public function create($class, Request $request)
    {
        $meta = new ReflectionClass($class);
        $props = $this->reflectionExtractor->extract($meta, $this->filterProps($meta));

        /** @var AbstractRequest $object */
        $object = $meta->newInstance();

        /** @var ExtractDTO $prop */
        foreach ($props as $prop) {
            $configuration = $prop->getConfiguration();

            /** @var class-string<AbstractRequest> $type */
            $type = $configuration->getType();

            if ($configuration->isDto() && $type && !$configuration->isBuiltInType()) {
                $request = clone $request;
                $value = $this->requestManager->get($prop, $request);
                $value = is_array($value) ? $value : [];
                $storage = $prop->getRequestStorage();

                if (!$storage) {
                    $request->query->replace($value);
                } elseif (in_array(RequestStorage::QUERY, $storage->getSource())) {
                    $request->query->replace($value);
                } elseif (in_array(RequestStorage::BODY, $storage->getSource())) {
                    $request->request->replace($value);
                } else {
                    $request->query->replace($value);
                }

                $var = $this->create($type, $request);
            } elseif ($configuration->isBuiltInType()) {
                $var = $this->requestManager->get($prop, $request);
            } else {
                $var = $this->requestManager->getFromParamConverters($prop, $request);
            }

            if (!$configuration->isOptional() && null === $var) {
                throw new UnprocessableEntityHttpException(
                    sprintf("Property '%s' cannot be empty", $prop->getName())
                );
            }

            if ($meta->hasMethod($method = 'set' . ucfirst($prop->getName()))) {
                $object->$method($var);
            } else {
                $object->{$prop->getName()} = $var;
            }
        }

        if (!$this->validator->validate($object)) {
            throw new UnprocessableEntityHttpException($this->validator->getError());
        }

        return $object;
    }


    /**
     * @param ReflectionClass<AbstractRequest> $meta
     *
     * @return string[]
     */
    private function filterProps(ReflectionClass $meta)
    {
        $props = array_filter(
            $meta->getProperties(),
            function (ReflectionProperty $prop) use ($meta) {
                $method = 'set' . ucfirst($prop->getName());

                return $prop->getDeclaringClass()->getName() !== Request::class &&
                    ($prop->isPublic() || ($meta->hasMethod($method) && $meta->getMethod($method)->isPublic()));
            }
        );

        return array_map(function (ReflectionProperty $property) {
            return $property->getName();
        }, $props);
    }
}
