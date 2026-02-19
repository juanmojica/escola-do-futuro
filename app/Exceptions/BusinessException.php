<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    protected $code = 422;

    public function __construct($message = 'Erro ao processar a operação.', $code = 422)
    {
        parent::__construct($message, $code);
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $this->message,
            ], $this->code);
        }

        return redirect()->back()
            ->with('error', $this->message)
            ->withInput();
    }
}
