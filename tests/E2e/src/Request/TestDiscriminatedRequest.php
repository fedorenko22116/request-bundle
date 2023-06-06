<?php

declare(strict_types=1);

namespace App\Request;

use App\Request\DTO\DiscriminatorParamsBar;
use App\Request\DTO\DiscriminatorParamsFoo;
use App\Request\DTO\EnumDiscriminatorParamsFoo;
use App\Request\DTO\EnumDiscriminatorParamsBar;
use LSBProject\RequestBundle\Configuration as LSB;
use LSBProject\RequestBundle\Contract\RequestInterface;

final class TestDiscriminatedRequest implements RequestInterface
{
    #[LSB\PropConverter(isDto: true)]
    #[LSB\Discriminator(
        field: 'type',
        mapping: [
            'foo' => DiscriminatorParamsFoo::class,
            'bar' => new LSB\PropConverter(class: DiscriminatorParamsBar::class, isDto: true)
        ]
    )]
    public DiscriminatorParamsFoo|DiscriminatorParamsBar $discriminated;

    #[LSB\PropConverter(isDto: true)]
    #[LSB\Discriminator(
        field: 'type',
        mapping: [
            'foo' => DiscriminatorParamsFoo::class,
            'bar' => DiscriminatorParamsBar::class
        ]
    )]
    public ?array $discriminatedCollection;

    #[LSB\PropConverter(isDto: true)]
    #[LSB\Discriminator(
        field: 'type',
        mapping: [
            'foo' => EnumDiscriminatorParamsFoo::class,
            'bar' => EnumDiscriminatorParamsBar::class
        ]
    )]
    public EnumDiscriminatorParamsFoo|EnumDiscriminatorParamsBar|null $enumDiscriminated;
}
