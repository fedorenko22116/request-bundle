<?php declare(strict_types=1);

namespace App\Request;

use App\Request\DTO\TestParams;
use LSBProject\RequestBundle\Configuration as LSB;

class TestJsonRpcRequest extends JsonRpcRequest
{
    /** @LSB\PropConverter(isDto=true) */
    public TestParams $params;
}
