<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * Customize the response for unauthenticated errors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            // Retornar um JSON com a mensagem de falha de autenticação
            return response()->json([
                'message' => 'Falha na autenticação. Token inválido ou não fornecido.'
            ], 401);
        }

        // Não redirecionamos para a página de login em APIs, apenas retornamos uma mensagem de erro
        return response()->json([
            'message' => 'Falha na autenticação. Token inválido ou não fornecido.'
        ], 401);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Log para acompanhar exceções e erros
        Log::error('Exception', [
            'exception' => $exception,
            'request' => $request->all(),
        ]);

        // Se for uma exceção de autenticação, retorna o erro de autenticação para APIs
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        // Caso contrário, renderiza o erro da maneira padrão
        return parent::render($request, $exception);
    }
}
