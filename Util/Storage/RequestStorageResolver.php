<?php

namespace LSBProject\RequestBundle\Util\Storage;

use LSBProject\RequestBundle\Configuration\RequestStorage;
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
        if (!$paramConfiguration) {
            return $this->requestStack->getCurrentRequest()->get($param);
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
        switch ($source) {
            case RequestStorage::QUERY:
                $result = $this->requestStack->getCurrentRequest()->query->get($param);

                if ($result) {
                    return $result;
                }
                break;
            case RequestStorage::BODY:
                $result = $this->requestStack->getCurrentRequest()->request->get($param);

                if ($result) {
                    return $result;
                }
                break;
            case RequestStorage::ATTR:
                $result = $this->requestStack->getCurrentRequest()->attributes->get($param);

                if ($result) {
                    return $result;
                }
                break;
        }

        return null;
    }
}
