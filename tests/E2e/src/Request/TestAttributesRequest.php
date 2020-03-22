<?php declare(strict_types=1);

namespace App\Request;

use App\Entity\TestEntity;
use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\AbstractRequest;

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

    /**
     * @RequestStorage({"query"})
     */
    public string $testId;

    /**
     * @Entity(options={"id"="test_id"})
     */
    public TestEntity $entity;
}
