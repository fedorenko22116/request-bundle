<?php declare(strict_types=1);

namespace App\Request;

use App\Entity\TestEntity;
use LSBProject\RequestBundle\Configuration as LSB;
use LSBProject\RequestBundle\Request\RequestInterface;

/**
 * @LSB\RequestStorage({LSB\RequestStorage::PATH})
 */
class TestAttributesRequestInterface implements RequestInterface
{
    public string $fooAttr;

    /**
     * @LSB\RequestStorage({LSB\RequestStorage::QUERY})
     */
    public string $bar;

    /**
     * @LSB\RequestStorage({LSB\RequestStorage::QUERY})
     */
    public int $testId;

    /**
     * @LSB\RequestStorage({LSB\RequestStorage::QUERY})
     * @LSB\PropConverter(name="bar_baz")
     */
    public string $baz;

    /**
     * @LSB\RequestStorage({LSB\RequestStorage::QUERY})
     * @LSB\Entity(options={"id": "test_id"})
     */
    public TestEntity $entityA;

    /**
     * @LSB\RequestStorage({LSB\RequestStorage::QUERY})
     * @LSB\Entity(expr="repository.find(id)", mapping={"id": "test_id"})
     */
    public TestEntity $entityB;

    /**
     * @LSB\RequestStorage({LSB\RequestStorage::QUERY})
     * @LSB\Entity(options={"mapping": {"bar_baz": "text"}})
     */
    public TestEntity $entityC;
}
