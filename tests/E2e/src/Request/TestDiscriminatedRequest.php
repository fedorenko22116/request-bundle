<?php

declare(strict_types=1);

namespace App\Request;

use App\Request\DTO\DiscriminatorParamsBar;
use App\Request\DTO\DiscriminatorParamsFoo;
use LSBProject\RequestBundle\Configuration as LSB;
use LSBProject\RequestBundle\Request\RequestInterface;

final class TestDiscriminatedRequest implements RequestInterface
{
    #[LSB\Discriminator(
        field: 'type',
        mapping: [
            'foo' => new LSB\PropConverter(class: DiscriminatorParamsFoo::class, isDto: true),
            'bar' => new LSB\PropConverter(class: DiscriminatorParamsBar::class, isDto: true)
        ]
    )]
    public DiscriminatorParamsFoo|DiscriminatorParamsBar $discriminated;
}
