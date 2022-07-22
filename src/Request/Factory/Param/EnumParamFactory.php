<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use Symfony\Component\HttpFoundation\Request;

final class EnumParamFactory implements ParamAwareFactoryInterface
{
    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * ConverterParamFactory constructor.
     *
     * @param RequestManagerInterface $requestManager
     */
    public function __construct(RequestManagerInterface $requestManager)
    {
        $this->requestManager = $requestManager;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(PropConfigurationInterface $configuration)
    {
        if (80100 <= PHP_VERSION_ID) {
            return enum_exists($configuration->getType() ?: '');
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request)
    {
        if (80100 <= PHP_VERSION_ID) {
            /** @var class-string<\BackedEnum> $enumType */
            $enumType = $data->getConfiguration()->getType();

            $reflector = new \ReflectionEnum($enumType);

            /** @var \ReflectionNamedType|null $backingType */
            $backingType = $reflector->getBackingType();

            if ($backingType) {
                $data->getConfiguration()->setType($backingType->getName());

                return $enumType::tryFrom($this->requestManager->get($data, $request));
            }
        }

        return null;
    }
}
