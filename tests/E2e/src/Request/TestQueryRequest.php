<?php declare(strict_types=1);

namespace App\Request;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\AbstractRequest;
use App\Entity\DTO\TestDTO;

/**
 * @RequestStorage({"query"})
 */
class TestQueryRequest extends AbstractRequest
{
    private string $foo;
    public bool $barBaz;
    public TestDTO $dto;

    public function setBarBaz(bool $value): void
    {
        $this->barBaz = $value;
    }

    public function getBarBaz(): bool
    {
        return $this->barBaz;
    }

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function setFoo(string $value): void
    {
        $this->foo = $value;
    }
}
