<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        ValidationException::class,
        AuthenticationException::class,
        AuthorizationException::class,
        TokenMismatchException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
    ];

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
        if ($this->shouldReport($exception)) {
            Log::error('Exception occurred', [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (method_exists($exception, 'render')) {
            return $exception->render($request);
        }

        $handlers = [
            ModelNotFoundException::class => 'handleModelNotFound',
            QueryException::class => 'handleQueryException',
            NotFoundHttpException::class => 'handleNotFound',
        ];

        foreach ($handlers as $exceptionClass => $method) {
            if ($exception instanceof $exceptionClass) {
                return $this->$method($request, $exception);
            }
        }

        if (!config('app.debug') && !($exception instanceof ValidationException)) {
            return $this->handleGenericError($request);
        }

        return parent::render($request, $exception);
    }

    private function handleModelNotFound($request, $exception)
    {
        return redirect()->back()->with('error', 'Registro não encontrado.');
    }

    private function handleQueryException($request, $exception)
    {
        $message = 'Erro ao processar operação no banco de dados. Entre em contato com o suporte.';
        
        if (config('app.debug')) {
            $message .= ' Detalhes: ' . $exception->getMessage();
        }

        return redirect()->back()->with('error', $message)->withInput();
    }

    private function handleNotFound($request, $exception)
    {
        return response()->view('errors.404', [], 404);
    }

    private function handleGenericError($request)
    {
        return redirect()->back()->with('error', 'Ocorreu um erro. Por favor, entre em contato com o suporte.');
    }
}
