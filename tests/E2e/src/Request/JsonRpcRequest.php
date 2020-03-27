<?php declare(strict_types=1);

namespace App\Request;

use LSBProject\RequestBundle\Configuration\RequestStorage;

/**
 * @RequestStorage({"body"})
 */
abstract class JsonRpcRequest
{
    public string $jsonrpc;
    public string $method;
    public int $id;
}
