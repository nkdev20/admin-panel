<?php

namespace App\Exceptions;


use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response as Responses;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->is('api/*')) {

            $status        = Responses::HTTP_BAD_REQUEST;
    
            $apiController = app()->make('App\Http\Controllers\ApiController');
            dd($e->getMessage());
            if ($e instanceof ModelNotFoundException) {
                $status = Responses::HTTP_NOT_FOUND;
            } elseif ($e instanceof AuthenticationException) {
                $status = Responses::HTTP_FORBIDDEN;
            } elseif ($e instanceof AuthorizationException) {
                $status = Responses::HTTP_UNAUTHORIZED;
            } elseif ($e instanceof ValidationException) {
                return $apiController->responseValidationBadRequest([
                    'message' => $e->validator->errors()->first()
                ]);
            } elseif ($e instanceof HttpException) {
                $status = $e->getStatusCode();
            } elseif ($e instanceof QueryException) {
                return $apiController->respondInternalServerError([
                    'message' => 'Internal server error'
                ]);
            }

            return $apiController->responseBadRequest([
                'message' => $e->getMessage(), $status
            ]);
        }
        return parent::render($request, $e);
    }
}
