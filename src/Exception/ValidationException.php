<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ValidationException extends BadRequestHttpException
{
    /**
     * @param array<string, string> $errors
     */
    public function __construct(public readonly array $errors = [])
    {
        parent::__construct();
    }
}
