<?php declare(strict_types=1);

namespace App\Request;

use App\Request\DTO\TestParams;
use LSBProject\RequestBundle\Configuration\PropConverter;

class TestJsonRpcRequest extends JsonRpcRequest
{
    /** @PropConverter(isDto=true) */
    public TestParams $params;
}
