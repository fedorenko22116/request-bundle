<?php declare(strict_types=1);

namespace App\Request;

use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\AbstractRequest;

/**
 * @RequestStorage({"body"})
 */
abstract class JsonRpcRequest extends AbstractRequest
{
    public string $jsonrpc;
    public int $id;

    /**
     * 'method' property already present in a base Request class, so alias should be used
     *
     * @PropConverter(name="method")
     */
    public string $methodName;
}
