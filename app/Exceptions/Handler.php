<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Report or log an exception.
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function render($request, Exception $e)
    {
        $this->request = $request;

        if ($e instanceof AuthorizationException) {
            $e = new AccessDeniedHttpException();
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException('Resource not found.');
        }

        if (env('APP_ENV') === 'testing' && ! $e instanceof HttpException) {
            throw $e;
        }

        return parent::render($request, $e);
    }

    /**
     * Create a Symfony response for the given exception.
     *
     * @param \Exception $e
     * @param string     $responseMessage
     * @param int        $code
     * @param array      $headers
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertExceptionToResponse(
        Exception $e,
        $responseMessage = 'Internal Server Error',
        $code = 500,
        $headers = []
    ) {
        if (! $this->request->wantsJson()) {
            return parent::convertExceptionToResponse($e);
        }

        $data = [
            'code'  => $code,
            'error' => $responseMessage,
        ];

        if (config('app.debug')) {
            $data['error'] = $e->getMessage();
            $data['int_code'] = $e->getCode();
            $data['file'] = $e->getFile();
            $data['line'] = $e->getLine();
            $data['trace'] = $e->getTrace();
        }

        return new JsonResponse($data, $code, $headers);
    }

    /**
     * Render the given HttpException.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpException $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        if (! $this->request->wantsJson()) {
            return parent::renderHttpException($e);
        }

        return $this->convertExceptionToResponse($e, $e->getMessage(), $e->getStatusCode(), $e->getHeaders());
    }
}
