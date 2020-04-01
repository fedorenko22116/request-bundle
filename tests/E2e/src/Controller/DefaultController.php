<?php declare(strict_types=1);

namespace App\Controller;

use App\Request\DTO\TestParamsA;
use App\Request\TestAttributesRequest;
use App\Request\TestBodyRequest;
use App\Request\TestJsonRpcRequest;
use App\Request\TestQueryRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractFOSRestController
{
    /**
     * @Rest\View()
     * @Route("/query", methods={"GET"})
     */
    public function testQueryRequest(TestQueryRequest $request): array
    {
        return [
            'foo' => $request->getFoo(),
            'barBaz' => $request->getBarBaz(),
            'dto' => $request->dto->getFoo()
        ];
    }

    /**
     * @Rest\View()
     * @Route("/body", methods={"POST"})
     */
    public function testBodyRequest(TestBodyRequest $request): array
    {
        return [
            'foo' => $request->foo,
            'barBaz' => $request->barBaz,
            'dto' => $request->dto->getFoo()
        ];
    }

    /**
     * @Rest\View()
     * @Route("/attributes/{foo_attr}")
     */
    public function testAttributesRequest(TestAttributesRequest $request): array
    {
        return [
            'foo' => $request->fooAttr,
            'bar' => $request->bar,
            'entityA' => $request->entityA->getText(),
            'entityB' => $request->entityB->getText(),
            'entityC' => $request->entityC->getText()
        ];
    }

    /**
     * @Rest\View()
     * @Route("/jsonrpc")
     */
    public function jsonrpcRequest(TestJsonRpcRequest $request): array
    {
        return [
            'jsonrpc' => $request->jsonrpc,
            'method' => $request->methodName,
            'id' => $request->id,
            'params' => [
                'foo' => $request->params->foo,
                'bar' => [
                    ['foo' => $request->params->bar[0]->foo],
                    ['foo' => $request->params->bar[1]->foo],
                ],
            ],
        ];
    }
}
