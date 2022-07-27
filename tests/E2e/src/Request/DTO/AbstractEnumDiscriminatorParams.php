<?php

declare(strict_types=1);

namespace App\Request\DTO;

use App\Request\Enum\FooEnum;

abstract class AbstractEnumDiscriminatorParams implements \JsonSerializable
{
    public FooEnum $type;

    public function jsonSerialize()
    {
        return (array) $this;
    }
}
