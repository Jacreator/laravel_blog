<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
        // handling validation error
        if ($exception instanceof ValidationException) {
            // call the converted validation respone
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        // handling Model error
        if ($exception instanceof ModelNotFoundException) {
            // get model name
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not exist any {$modelName} with the specified identificator", 404);
        }

        // handling unauthenticated error
        if ($exception instanceof AuthenticationException) {
            # call the unauthenticated method and pass request before exception
            return $this->unauthenticated($request, $exception);
        }

        // handling Authorization error
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), 403);
        }

        // handling Url not found error
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("The specified Url cannot be found", 404);
        }

        // handling Method not allowed error
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse("The specified Method for this request is invalid", 405);
        }

        // handling any other error
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        // handling delete error
        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return $this->errorResponse("Cannot Remove this resource parmenently. It is related to other resources", 409);
            }
            // could not connect to db
            if ($errorCode == 2002) {
                return $this->errorResponse("Database Server is down", 409);
            }

        }

        // handling token mismatch execption
        if ($exception instanceof TokenMismatchException) {
            redirect()->back()->withInput($request->input());
        }

        // handling life unexcepted error
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse("Unexcepted Exception, Please Try Again Later", 500);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        // check what kind of error
        if ($this->isFrontEnd($request)) {
            return $request->ajax() ? $this->errorResponse($errors, 422) : redirect()
            ->back()
            ->withInput($request->input())
            ->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontEnd($request)) {
            redirect()->guest('login');
        }
        return $this->errorResponse('Unauthenticated', 401);

    }

    private function isFrontEnd($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
