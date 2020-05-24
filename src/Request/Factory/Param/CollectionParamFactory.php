<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use Symfony\Component\HttpFoundation\Request;

class CollectionParamFactory implements ParamAwareFactoryInterface
{
    use RequestCopyTrait;

    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var callable
     */
    private $callback;

    /**
     * ConverterParamFactory constructor.
     *
     * @param RequestManagerInterface $requestManager
     * @param callable                $callback
     */
    public function __construct(RequestManagerInterface $requestManager, $callback)
    {
        $this->requestManager = $requestManager;
        $this->callback = $callback;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(PropConfigurationInterface $configuration)
    {
        return $configuration->isDto()
            && $configuration->getType()
            && !$configuration->isBuiltInType()
            && $configuration->isCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function create(ExtractDTO $data, Request $request, PropConfigurationInterface $configuration)
    {
        $params = $this->requestManager->get($data, $request);
        $params = is_array($params) ? $params : [];

        $callback = $this->callback;

        return array_map(function (array $param) use ($request, $data, $configuration, $callback) {
            return $callback(
                $configuration->getType(),
                $this->cloneRequest($request, $param, $data->getRequestStorage())
            );
        }, $params);
    }
}
