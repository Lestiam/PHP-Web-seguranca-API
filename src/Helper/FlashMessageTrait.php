<?php

namespace Alura\Mvc\Helper;

trait FlashMessageTrait //eu só posso usar Traits (use), eu não posso instanciar traits e por isso o método pode ser privado, pq é como se a outra classe copiasse tudo que está aqui, para ela
{
    private function addErrorMessage(string $errorMessage): void
    {
        $_SESSION['error_message'] = $errorMessage;
    }
}