<?php

namespace Alura\Mvc\Controller;

class LoginController implements Controller
{
    private \PDO $pdo;

    public function __construct()
    {
        $dbPath = __DIR__ . '/../../banco.sqlite';
        $this->pdo = new \PDO("sqlite:$dbPath");
    }

    public function processaRequisicao(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); //busca os dados do formulario e valida se o e-mail esta correto. Se o email vier incorreto ou vazio, então ele nem faz nada
        $password = filter_input(INPUT_POST, 'password');

        $sql = 'SELECT * FROM users WHERE email = ?;'; //seleciona todos os usuario onde o e-mail for igual ao e-mail que eu receber por paramretro (NO FORMULARIO)
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $email);
        $statement->execute();

        $userData = $statement->fetch(\PDO::FETCH_ASSOC); //se o e-mail estiver correto, ele valida com os dados do usuario
        $correctPassword = password_verify($password, $userData['password'] ?? ''); //compara a senha com o hash armazenado no banco. Essa função verifica qual foi o algoritmo utilizado para gerar o hash da senha, com qual vetor de inicialização, com qual processamento, entre outros detalhes. E se o e-mail não der certo,na senha, ele devolve para a gente uma string vazia

        if (password_needs_rehash($userData['password'], PASSWORD_ARGON2ID)) { //verifica se a senha do usuario esta utilizando o hash mais atual, se não estiver, atualiza o hash
            $statement = $this->pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $statement->bindValue(1, password_hash($password, PASSWORD_ARGON2ID));
            $statement->bindValue(2, $userData['id']);
            $statement->execute();
        }

        //eu nunca posso exibir algo antes de iniciar a session, pois vai dar erro ao enviar a requisição
        if ($correctPassword) { //se for verdadeiro...
            $_SESSION['logado'] = true; //super global que verifica através do coockie do navegador se o usuario esta logado
            header('Location: /'); //redireciona para a listagem de videos
        } else {
            header('Location: /login?sucesso=0');
        }
    }
}