<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\ValueResolver;

use LSBProject\RequestBundle\Contract\RequestInterface;
use LSBProject\RequestBundle\Mapping\MapperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class RequestResolver implements ValueResolverInterface
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
        if (!is_a($argument->getType(), RequestInterface::class, true)) {
            return;
        }

        yield $this->requestFactory->map($argument->getType(), $request);
    }
}
