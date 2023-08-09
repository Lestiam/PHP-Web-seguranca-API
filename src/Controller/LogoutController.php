<?php

namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogoutController implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //Isso é mais seguro, pois a destruição de sessões pode trazer resultados inesperados em casos de requisições concorrentes.
        // Embora isso seja um assunto mais avançado e um problema raro, é interessante saber que há uma alternativa bem simples que não incorre na mesma situação
        session_destroy(); //esta função apaga o cookie da sessão e automaticamente desloga o usuario
        return new Response(302, ['Location' => '/login']);

    }
}