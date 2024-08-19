<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        TokenMismatchException::class,
        ValidationException::class,
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        if ($e->getMessage() != "Unauthenticated.") {
            $route = $request->getrequestUri();
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            if ($msg != '' && $msg != null) {
                $user = Auth::user();
                $user_id = null;
                if($user) {
                    $user_id = $user->id;
                }
                DB::table('error_log')->insert(['file' => $file, 'line' => $line, 'message' => $msg, 'route' => $route, 'user_id' => $user_id]);
            }
        }


        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        if ($e instanceof TokenMismatchException) {
            return redirect()->back()
                ->withInput($request->except('password'))
                ->withErrors(trans('core::core.error token mismatch'));
        }

        if (config('app.debug') === false) {
            return $this->handleExceptions($e);
        }

        return parent::render($request, $e);
    }

    private function handleExceptions($e)
    {
        if ($e instanceof ModelNotFoundException) {
            return response()->view('errors.404', [], 404);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        return response()->view('errors.500', [], 500);
    }
}
