<?php declare(strict_types=1);

namespace App\Request\DTO;

use App\Entity\TestEntity;
use LSBProject\RequestBundle\Configuration as LSB;

class TestParams
{
    public string $foo;

    /**
     * @var TestParamsA[]
     */
    #[LSB\PropConverter(isDto: true)]
    public array $bar;

    /**
     * @var TestEntity[]
     */
    #[LSB\PropConverter(options: ["mapping" => ["text" => "text"]])]
    public array $baz;
}
