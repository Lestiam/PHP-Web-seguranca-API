<?php

$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");

$email = $argv[1]; //essa variavel argv permite que peguemos as informações via terminal
$password = $argv[2];
$hash = password_hash($password, PASSWORD_ARGON2ID); //função que usa um hash para "encriptar" a senha, o primeiro parametro é a minha senha e o segundo é a forma como o hash será feito, nesse momento, PASSWORD_ARGON2ID é o qu tem de mais moderno para gerar um hash na senha

$sql = 'INSERT INTO users (email, password) VALUES (?, ?);';
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $email);
$statement->bindValue(2, $hash); //vou armazenar somente o meu hash e não a minha senha, para ninguem ter acesso a senha original do usuario
$statement->execute();