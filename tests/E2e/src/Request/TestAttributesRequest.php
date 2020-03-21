<?php declare(strict_types=1);

namespace App\Request;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\AbstractRequest;
use App\Entity\DTO\TestDTO;

/**
 * @RequestStorage({"attributes"})
 */
class TestAttributesRequest extends AbstractRequest
{
    public string $fooAttr;

    /**
     * @RequestStorage({"query"})
     */
    public string $bar;
}
