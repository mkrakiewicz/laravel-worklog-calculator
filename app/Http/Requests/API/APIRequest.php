<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;

class APIRequest extends FormRequest
{
    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return Response::json(ResponseUtil::makeError($errors), 400);
    }
}
