<?php

namespace LSBProject\RequestBundle\Util\Storage;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStorageResolver implements StorageInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritDoc}
     */
    public function get($param, $paramConfiguration = null, Request $request = null)
    {
        /** @var Request $request */
        $request = $request ?: $this->requestStack->getCurrentRequest();

        if (!$paramConfiguration) {
            return $request->get($param);
        }

        foreach ($paramConfiguration->getSource() as $source) {
            $result = $this->getFromStorage($source, $param);

            if ($result) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @param string       $source
     * @param string       $param
     * @param Request|null $request
     *
     * @return mixed|null
     */
    private function getFromStorage($source, $param, Request $request = null)
    {
        /** @var Request $request */
        $request = $request ?: $this->requestStack->getCurrentRequest();
        $result = null;

        switch ($source) {
            case RequestStorage::QUERY:
                $result = $request->query->get($param);

                break;
            case RequestStorage::BODY:
                $result = $request->request->get($param);

                break;
            case RequestStorage::ATTR:
                $result = $request->attributes->get($param);

                break;
        }

        return $result;
    }
}
