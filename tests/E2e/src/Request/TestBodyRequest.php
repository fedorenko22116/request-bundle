<?php declare(strict_types=1);

namespace App\Request;

use App\Entity\DTO\TestDTO;
use LSBProject\RequestBundle\Configuration as LSB;
use LSBProject\RequestBundle\Contract\RequestInterface;

/**
 * @LSB\RequestStorage({LSB\RequestStorage::BODY})
 */
class TestBodyRequest implements RequestInterface
{
    public string $foo;
    public bool $barBaz;
    public TestDTO $dto;
}
