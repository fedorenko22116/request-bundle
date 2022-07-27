<?php

declare(strict_types=1);

namespace App\Request\Enum;

enum FooEnum: string implements \JsonSerializable
{
    case Foo = 'foo';
    case Bar = 'bar';

    public function jsonSerialize()
    {
        return $this->value;
    }
}
