<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

abstract class Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
