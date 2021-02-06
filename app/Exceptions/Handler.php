<?php

namespace App\Exceptions;

use http\Exception;
use HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AccessDeniedHttpException) {
            return response()->json([
                'message' =>'Forbidden',
                'code' => '400',
            ], 400);
        }
        elseif ($exception instanceof NotFoundHttpException
        || $exception instanceof HttpException) {
            return response()->json([
                'message' =>'Resource Not Found',
                'code' => '404',
            ], 404);
        }
        else{
            return response()->json(
                [
                    'message' => 'Unexpected Error '.$exception->getMessage(),
                ], 422
            );
        }

    }
}
