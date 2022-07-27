<?php

namespace LSBProject\RequestBundle\Request\Factory\Param\Collection;

use LSBProject\RequestBundle\Request\Factory\Param\DiscriminatedParamFactory;
use LSBProject\RequestBundle\Request\Factory\Param\ParamAwareFactoryInterface;
use LSBProject\RequestBundle\Request\Factory\Param\RequestCopyTrait;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
use Symfony\Component\HttpFoundation\Request;

class CollectionDiscriminatedParamFactory implements ParamAwareFactoryInterface
{
    use RequestCopyTrait;

    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var DiscriminatedParamFactory
     */
    private $discriminatedParamFactory;

    /**
     * ConverterParamFactory constructor.
     *
     * @param RequestManagerInterface   $requestManager
     * @param DiscriminatedParamFactory $discriminatedParamFactory
     */
    public function __construct(
        RequestManagerInterface $requestManager,
        DiscriminatedParamFactory $discriminatedParamFactory
    ) {
        $this->requestManager = $requestManager;
        $this->discriminatedParamFactory = $discriminatedParamFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Extraction $data)
    {
        return (bool) $data->getDiscriminator();
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request)
    {
        $params = $this->requestManager->get($data, $request);
        $params = is_array($params) ? $params : [];

        $name = md5($data->getName());

        $data = clone $data;
        $data->getConfiguration()->setIsCollection(false);
        $data->setName($name);

        return array_map(function (array $param) use ($request, $data, $name) {
            return $this->discriminatedParamFactory->create(
                $data,
                $this->cloneRequest($request, [$name => $param], $data->getRequestStorage())
            );
        }, $params);
    }
}
