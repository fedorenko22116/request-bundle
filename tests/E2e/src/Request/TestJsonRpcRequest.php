<?php declare(strict_types=1);

namespace App\Request;

use App\Request\DTO\TestParams;

class TestJsonRpcRequest extends JsonRpcRequest
{
    public TestParams $params;
}
