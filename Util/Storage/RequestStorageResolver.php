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
    public function get($param, $paramConfiguration = null)
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

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
     * @param string $source
     * @param string $param
     * @return mixed|null
     */
    private function getFromStorage($source, $param)
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        switch ($source) {
            case RequestStorage::QUERY:
                $result = $request->query->get($param);

                if ($result) {
                    return $result;
                }
                break;
            case RequestStorage::BODY:
                $result = $request->request->get($param);

                if ($result) {
                    return $result;
                }
                break;
            case RequestStorage::ATTR:
                $result = $request->attributes->get($param);

                if ($result) {
                    return $result;
                }
                break;
        }

        return null;
    }
}
