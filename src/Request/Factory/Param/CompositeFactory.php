<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use Symfony\Component\HttpFoundation\Request;

class CompositeFactory implements ParamAwareFactoryInterface
{
    /**
     * @var ParamAwareFactoryInterface[]
     */
    private $composites;

    /**
     * CompositeFactory constructor.
     *
     * @param RequestManagerInterface $requestManager
     * @param callable                $callback
     */
    public function __construct(RequestManagerInterface $requestManager, $callback)
    {
        $this->composites = [
            new CollectionParamFactory($requestManager, $callback),
            new DtoParamFactory($requestManager, $callback),
            new ConverterParamFactory($requestManager),
            new ScalarParamFactory($requestManager),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports(PropConfigurationInterface $configuration)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function create(ExtractDTO $data, Request $request, PropConfigurationInterface $configuration)
    {
        foreach ($this->composites as $composite) {
            if ($composite->supports($configuration)) {
                return $composite->create($data, $request, $configuration);
            }
        }

        return null;
    }
}
