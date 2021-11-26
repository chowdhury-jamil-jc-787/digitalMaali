<?php

namespace App\Exceptions;

use App\Helpers\ApiResponser;
use App\Models\FarmerInfo;
use BadMethodCallException;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
  use ApiResponser;
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

    $this->renderable(function (Exception $exception, $request) {
      if ($request->wantsJson()) {

        if ($exception instanceof HttpException) {
          $code = $exception->getStatusCode();
          $message = $exception->getMessage();

          if (empty($message)) {
            $message = 'Route not found';
          }

          return $this->errorResponse($message, $code);
        }

        if ($exception instanceof AuthenticationException) {
          return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof AuthorizationException) {
          return $this->errorResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof ValidationException) {
          $errors = $exception->validator->errors()->getMessages();
          $errors = current($errors)[0];

          return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!env('APP_DEBUG')) {
          return $this->errorResponse('Unexpected error occurred. Please contact to your administrator for help.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    });
  }
}
