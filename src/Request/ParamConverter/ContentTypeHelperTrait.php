<?php

namespace LSBProject\RequestBundle\Request\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait ContentTypeHelperTrait
{
    /**
     * @param Request $request
     *
     * @return void
     */
    private function convertRequestContextIfEmpty(Request $request)
    {
        if ($request->getContentType() !== 'json' || !$request->getContent()) {
            return;
        }

        $data = json_decode((string) $request->getContent(false), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException(sprintf('Invalid json body: %s', json_last_error_msg()));
        }

        $request->request->replace(is_array($data) ? $data : []);
    }
}
