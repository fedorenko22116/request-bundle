<?php declare(strict_types=1);

namespace App\Entity\DTO;

class TestDTO 
{
    private string $foo;
    
    public function __construct(string $foo)
    {
        $this->foo = $foo;
    }

    public function getFoo(): string
    {
        return $this->foo;
    }
}
