<?php declare(strict_types=1);

namespace App\Request;

use App\Entity\TestEntity;
use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\PropConverter;
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
    public int $testId;

    /**
     * @RequestStorage({"query"})
     * @PropConverter(name="bar_baz")
     */
    public string $baz;

    /**
     * @Entity(options={"id": "test_id"})
     */
    public TestEntity $entityA;

    /**
     * @Entity(expr="repository.find(id)", mapping={"id": "test_id"})
     */
    public TestEntity $entityB;

    /**
     * @Entity(options={"mapping": {"bar_baz": "text"}})
     */
    public TestEntity $entityC;
}
