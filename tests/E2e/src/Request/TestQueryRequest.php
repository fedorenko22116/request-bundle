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
    public string $foo;
    private bool $barBaz;
    public TestDTO $dto;

    public function setBarBaz(bool $value): void
    {
        $this->barBaz = $value;
    }

    public function getBarBaz(): bool
    {
        return $this->barBaz;
    }
}
