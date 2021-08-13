<?php declare(strict_types=1);

namespace App\Request;

use LSBProject\RequestBundle\Configuration as LSB;
use LSBProject\RequestBundle\Request\RequestInterface;

/**
 * @LSB\RequestStorage({LSB\RequestStorage::BODY})
 */
abstract class JsonRpcRequestInterface implements RequestInterface
{
    public string $jsonrpc;
    public int $id;

    /**
     * 'method' property already present in a base Request class, so alias should be used
     *
     * @LSB\PropConverter(name="method")
     */
    public string $methodName;
}
