<?php declare(strict_types=1);

namespace App\Request\DTO;

use App\Entity\TestEntity;
use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\PropConverter;

class TestParams
{
    public string $foo;

    /**
     * @PropConverter("App\Request\DTO\TestParamsA", isCollection=true, isDto=true)
     * @var TestParamsA[]
     */
    public array $bar;

    /**
     * @Entity("App\Entity\TestEntity", isCollection=true, options={"mapping": {"text": "text"}})
     * @var TestEntity[]
     */
    public array $baz;
}
