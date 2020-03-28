<?php declare(strict_types=1);

namespace App\Request;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\AbstractRequest;

/**
 * @RequestStorage({"body"})
 */
abstract class JsonRpcRequest extends AbstractRequest
{
    public string $jsonrpc;
    public string $method;
    public int $id;
}
