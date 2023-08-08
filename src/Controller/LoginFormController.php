<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\HtmlRendererTrait;

class LoginFormController implements Controller
{
    use HtmlRendererTrait;
    public function processaRequisicao(): void
    {
        if (array_key_exists('logado', $_SESSION) && $_SESSION['logado'] === true) {
            header('Location: /'); //se o usuario estiver logado, eu não deixo ele acessar novamente a página de login
            return;
        }
        echo $this->renderTemplate('login-form');
    }
}