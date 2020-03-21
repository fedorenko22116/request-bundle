<?php declare(strict_types=1);

namespace App\Controller;

use App\Request\TestAttributesRequest;
use App\Request\TestBodyRequest;
use App\Request\TestQueryRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/query", methods={"GET"})
     */
    public function testQueryRequest(TestQueryRequest $request): JsonResponse
    {
        return new JsonResponse([
            'foo' => $request->foo,
            'barBaz' => $request->barBaz,
            'dto' => $request->dto->getFoo()
        ]);
    }

    /**
     * @Route("/body", methods={"POST"})
     */
    public function testBodyRequest(TestBodyRequest $request): JsonResponse
    {
        return new JsonResponse([
            'foo' => $request->foo,
            'barBaz' => $request->barBaz,
            'dto' => $request->dto->getFoo()
        ]);
    }

    /**
     * @Route("/attributes/{foo_attr}")
     */
    public function testAttributesRequest(TestAttributesRequest $request): JsonResponse
    {
        return new JsonResponse([
            'foo' => $request->fooAttr,
            'bar' => $request->bar
        ]);
    }
}
