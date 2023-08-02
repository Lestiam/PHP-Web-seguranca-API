<?php

namespace Alura\Mvc\Controller;

class LogoutController implements Controller
{

    public function processaRequisicao(): void
    {
        //session_destroy(); //esta função apaga o cookie da sessão e automaticamente desloga o usuario
        //Isso é mais seguro, pois a destruição de sessões pode trazer resultados inesperados em casos de requisições concorrentes.
        // Embora isso seja um assunto mais avançado e um problema raro, é interessante saber que há uma alternativa bem simples que não incorre na mesma situação
        $_SESSION['logado'] = false;
        unset($_SESSION['logado']);
        header('Location: /login');
    }
}