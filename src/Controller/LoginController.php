<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginController implements RequestHandlerInterface
{
    use FlashMessageTrait;
    private \PDO $pdo;

    public function __construct()
    {
        $dbPath = __DIR__ . '/../../banco.sqlite';
        $this->pdo = new \PDO("sqlite:$dbPath");
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); //busca os dados do formulario e valida se o e-mail esta correto. Se o email vier incorreto ou vazio, então ele nem faz nada
        $password = filter_input(INPUT_POST, 'password');

        $sql = 'SELECT * FROM users WHERE email = ?;'; //seleciona todos os usuario onde o e-mail for igual ao e-mail que eu receber por paramretro (NO FORMULARIO)
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $email);
        $statement->execute();

        $userData = $statement->fetch(\PDO::FETCH_ASSOC); //se o e-mail estiver correto, ele valida com os dados do usuario
        $correctPassword = password_verify($password, $userData['password'] ?? ''); //compara a senha com o hash armazenado no banco. Essa função verifica qual foi o algoritmo utilizado para gerar o hash da senha, com qual vetor de inicialização, com qual processamento, entre outros detalhes. E se o e-mail não der certo,na senha, ele devolve para a gente uma string vazia

        if (!$correctPassword) {
            $this->addErrorMessage('Usário ou senha inválidos');
            return new Response(302, ['Location' => '/login']);
        }

        if (password_needs_rehash($userData['password'], PASSWORD_ARGON2ID)) { //verifica se a senha do usuario esta utilizando o hash mais atual, se não estiver, atualiza o hash
            $statement = $this->pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $statement->bindValue(1, password_hash($password, PASSWORD_ARGON2ID));
            $statement->bindValue(2, $userData['id']);
            $statement->execute();
        }

        $_SESSION['logado'] = true;
        return new Response(302, ['Location' => '/login']);
    }
}