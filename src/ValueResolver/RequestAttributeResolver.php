<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\ValueResolver;

use LSBProject\RequestBundle\Mapping\MapperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class RequestAttributeResolver implements ValueResolverInterface
{
    public function __construct(private readonly MapperInterface $requestFactory)
    {
    }

    /**
     * @return object[]
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var \LSBProject\RequestBundle\Configuration\Request[] $filterAttributes */
        $filterAttributes = $argument->getAttributes(\LSBProject\RequestBundle\Configuration\Request::class);

        if (!$filterAttributes) {
            return;
        }

        $attribute = current($filterAttributes);

        yield $this->requestFactory->map($argument->getType(), $request, $attribute->getStorage());
    }
}
