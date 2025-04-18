<?php

namespace App\Traits;

use Jiannei\Response\Laravel\Support\Traits\ExceptionTrait;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Jiannei\Response\Laravel\Support\Facades\Response;

/**
 * 
 */
trait ResponseTrait
{
    use ExceptionTrait;

    use ExceptionTrait {
        ExceptionTrait::invalidJson as parentInvalidJson;
    }

    protected function invalidJson($request, ValidationException $exception){
        return Response::fail(
            Arr::get(Config::get('response.exception'), ValidationException::class.'.message', join(',', $exception->validator->errors()->all())),
            Arr::get(Config::get('response.exception'), ValidationException::class.'.code', 422),
            $exception->errors()
        );
    }
}
