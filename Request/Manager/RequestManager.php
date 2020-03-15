<?php

namespace LSBProject\RequestBundle\Request\Manager;

use LSBProject\RequestBundle\Util\Factory\ParamConverterFactoryInterface;
use LSBProject\RequestBundle\Util\NamingConversion\NamingConversionInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use LSBProject\RequestBundle\Util\Storage\StorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestManager implements RequestManagerInterface
{
    /**
     * @var NamingConversionInterface
     */
    private $namingConversion;

    /**
     * @var ParamConverterFactoryInterface
     */
    private $paramConverterFactory;

    /**
     * @var ParamConverterManager
     */
    private $converterManager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @param NamingConversionInterface $namingConversion
     * @param ParamConverterFactoryInterface $paramConverterFactory
     * @param ParamConverterManager $converterManager
     * @param StorageInterface $storage
     * @param RequestStack $requestStack
     */
    public function __construct(
        NamingConversionInterface $namingConversion,
        ParamConverterFactoryInterface $paramConverterFactory,
        ParamConverterManager $converterManager,
        StorageInterface $storage,
        RequestStack $requestStack
    ) {
        $this->namingConversion = $namingConversion;
        $this->paramConverterFactory = $paramConverterFactory;
        $this->converterManager = $converterManager;
        $this->storage = $storage;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritDoc}
     */
    public function get(ExtractDTO $param)
    {
        return $this->storage->get(
            $this->namingConversion->convert($param->getConfiguration()->getName()),
            $param->getRequestStorage()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getFromParamConverters(ExtractDTO $param)
    {
        $request = $this->requestStack->getCurrentRequest();
        $options = $param->getConfiguration()->getOptions();
        $id = isset($options['id']) ? $options['id'] : $this->storage->get('id', $param->getRequestStorage());

        if ($id) {
            $request->attributes->set(
                $id,
                $this->storage->get($this->namingConversion->convert($id), $param->getRequestStorage())
            );
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

            $request->attributes->set(
                $alias,
                $this->storage->get($this->namingConversion->convert($alias), $param->getRequestStorage())
            );
        }

        $paramConfig = $this->paramConverterFactory->create($param->getConfiguration());
        $this->converterManager->apply($request, $paramConfig);
        $var = $request->attributes->get($param->getConfiguration()->getName());
        $request->attributes->remove($param->getConfiguration()->getName());

        if ($id) {
            $request->attributes->remove($id);
        }

        foreach ($mapping as $alias => $option) {
            $request->attributes->remove($alias);
        }

        return $var;
    }
}
