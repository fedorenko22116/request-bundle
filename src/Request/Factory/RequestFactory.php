<?php

namespace LSBProject\RequestBundle\Request\Factory;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Exception\BadRequestException;
use LSBProject\RequestBundle\Request\Factory\Param\CompositeFactory;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Request\RequestInterface;
use LSBProject\RequestBundle\Request\Validator\RequestValidatorInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
use LSBProject\RequestBundle\Util\ReflectionExtractor\ReflectionExtractorInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

final class RequestFactory implements RequestFactoryInterface
{
    use RequestPropertyHelperTrait;

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
     *
     * @throws ReflectionException
     * @throws BadRequestException
     */
    public function create($class, Request $request, RequestStorage $requestStorage = null)
    {
        $meta = new ReflectionClass($class);
        $compositeFactory = new CompositeFactory($this->requestManager, $this);
        $props = $this->reflectionExtractor->extract($meta, $this->filterProps($meta));

        /** @var RequestInterface $object */
        $object = $meta->newInstance();

        /** @var Extraction $prop */
        foreach ($props as $prop) {
            if ($requestStorage && !$prop->getRequestStorage()) {
                $prop->setRequestStorage($requestStorage);
            }

            $var = $compositeFactory->create($prop, $request);

            if (null === $var && ($prop->isDefault() || !$prop->getConfiguration()->isOptional())) {
                continue;
            }

            if (PHP_VERSION_ID >= 70000) {
                try {
                    $this->setObjectVariable($object, $var, $meta, $prop);
                } catch (\TypeError $error) {
                    // phpcs:ignore
                    throw new BadRequestException(
                        sprintf(
                            'Type mismatch for property "%s". Expected value of type %s, "%s" given',
                            $prop->getName(),
                            $prop->getConfiguration()->getType(),
                            $var === null ? 'null' : $var
                        )
                    );
                }
            } else {
                $this->setObjectVariable($object, $var, $meta, $prop);
            }
        }

        $errors = $this->validator->validate($object);

        if ($errors) {
            throw new BadRequestException(current($errors));
        }

        return $object;
    }

    /**
     * @template T of object
     *
     * @param T                  $object
     * @param mixed              $var
     * @param ReflectionClass<T> $meta
     * @param Extraction         $prop
     *
     * @return void
     *
     * @throws \TypeError
     */
    private function setObjectVariable($object, $var, ReflectionClass $meta, Extraction $prop)
    {
        if ($meta->hasMethod($method = 'set' . ucfirst($prop->getName()))) {
            $object->$method($var);
        } else {
            $object->{$prop->getName()} = $var;
        }
    }
}
