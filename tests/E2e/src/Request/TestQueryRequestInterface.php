<?php declare(strict_types=1);

namespace App\Request;

use LSBProject\RequestBundle\Configuration as LSB;
use App\Entity\DTO\TestDTO;
use LSBProject\RequestBundle\Request\RequestInterface;

/**
 * @LSB\RequestStorage({LSB\RequestStorage::QUERY})
 */
class TestQueryRequestInterface implements RequestInterface
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
        $this->foo = 'Pre' . $value;
    }
}
