<?php declare(strict_types=1);

namespace App\Request\DTO;

use LSBProject\RequestBundle\Configuration\PropConverter;

class TestParams
{
    public string $foo;

    /**
     * @PropConverter("App\Request\DTO\TestParamsA", isCollection=true, isDto=true)
     * @var TestParamsA[]
     */
    public array $bar;
}
