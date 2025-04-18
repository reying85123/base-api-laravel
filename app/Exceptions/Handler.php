<?php

namespace App\Exceptions;

use App\Enums\ResponseCodeEnum;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\ResponseTrait;
use Throwable;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\Services\ModelServiceDataException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            return parent::render($request, new UnauthorizedHttpException(
                'jwt-auth',
                trans('jwtAuth.' . $exception->getMessage()),
                null,
                ResponseCodeEnum::UNAUTHORIZED_ACCESS
            ));
        }

        if ($exception instanceof ModelServiceDataException) {
            return parent::render($request, new HttpException(409, $exception->getMessage()));
        }

        return parent::render($request, $exception);
    }
}
