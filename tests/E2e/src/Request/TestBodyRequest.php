<?php declare(strict_types=1);

namespace App\Request;

use LSBProject\RequestBundle\Request\AbstractRequest;
use App\Entity\DTO\TestDTO;
use LSBProject\RequestBundle\Configuration as LSB;

/**
 * @LSB\RequestStorage({LSB\RequestStorage::BODY})
 */
class TestBodyRequest extends AbstractRequest
{
    public string $foo;
    public bool $barBaz;
    public TestDTO $dto;
}
