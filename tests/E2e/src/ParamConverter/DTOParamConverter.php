<?php declare(strict_types=1);

namespace App\ParamConverter;

use App\Entity\DTO\TestDTO;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class DTOParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $request->attributes->set($configuration->getName(), new TestDTO('SomeAwesomeText'));

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === TestDTO::class;
    }
}
