<?php

namespace LSBProject\RequestBundle\Request\Factory;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Exception\BadRequestException;
use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Request\Factory\Param\CompositeFactory;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Request\Validator\RequestValidatorInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use LSBProject\RequestBundle\Util\ReflectionExtractor\ReflectionExtractorInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;

final class RequestFactory implements RequestFactoryInterface
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
     * @throws BadRequestException
     */
    public function create(
        $class,
        Request $request,
        PropConfigurationInterface $configuration = null,
        RequestStorage $parentStorage = null
    ) {
        $meta = new ReflectionClass($class);
        $compositeFactory = new CompositeFactory($this->requestManager, $this);
        $props = $this->reflectionExtractor->extract($meta, $this->filterProps($meta));

        /** @var AbstractRequest $object */
        $object = $meta->newInstance();

        /** @var Extraction $prop */
        foreach ($props as $prop) {
            if (!$prop->getRequestStorage() && $parentStorage) {
                $prop->setRequestStorage($parentStorage);
            }

            $finalConfiguration = $configuration ?: $prop->getConfiguration();
            $var = $compositeFactory->create($prop, $request, $finalConfiguration);

            if (null === $var) {
                if ($prop->isDefault()) {
                    continue;
                }

                if (!$finalConfiguration->isOptional()) {
                    throw new BadRequestException(
                        sprintf("Property '%s' cannot be empty", $prop->getName())
                    );
                }
            }

            if ($meta->hasMethod($method = 'set' . ucfirst($prop->getName()))) {
                $object->$method($var);
            } else {
                $object->{$prop->getName()} = $var;
            }
        }

        if (!$this->validator->validate($object)) {
            throw new BadRequestException($this->validator->getError());
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
