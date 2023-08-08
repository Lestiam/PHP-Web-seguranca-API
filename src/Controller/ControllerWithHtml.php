<?php

namespace Alura\Mvc\Controller;

abstract class ControllerWithHtml implements Controller
{
    private const TEMPLATE_PATH = __DIR__ . '/../../views/';

    protected function renderTemplate(string $templateName, array $context = []): string //o array inicia como =[] pois ele é um parametro opcional, ou seja, não tem variável nenhuma
    {
        extract($context); //essa função pega um array associativo e extrai todas as suas chaves para que elas virem variáveis

        ob_start(); //Inicializa um buffer de saída, ou seja, um local que você vai armazenando tudo que seria exibido na tela, mas não exibe, só vai guardando os dados lá
        require_once self::TEMPLATE_PATH . $templateName . '.php'; //como não é uma constante global, eu preciso acessa-la da minha propria classe, por isso a palavra "self"
        return ob_get_clean(); //me dá o conteúdo desse buffer e depois limpa o buffer
    }
}