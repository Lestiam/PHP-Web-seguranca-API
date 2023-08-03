<?php

declare(strict_types=1);

use Alura\Mvc\Controller\{
    Controller,
    DeleteVideoController,
    EditVideoController,
    Error404Controller,
    NewVideoController,
    VideoFormController,
    VideoListController
};
use Alura\Mvc\Repository\VideoRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$dbPath = __DIR__ . '/../banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");
$videoRepository = new VideoRepository($pdo);

$routes = require_once __DIR__ . '/../config/routes.php';

$pathInfo = $_SERVER['PATH_INFO'] ?? '/';
$httpMethod = $_SERVER['REQUEST_METHOD'];

//no index é o melhor lugar para ter o session start, pois ele redireciona para as demais páginas
session_start(); //antes de fazer o $_SESSION, eu sempre preciso dar um session_start. Gera a sessão para armazenar as informações do usuario no cookie do navegador

//Dessa forma, a sessão anterior não terá mais a informação de autenticação e a nova sessão terá a informação que já havia sido salva.
if (isset($_SESSION['logado'])) {
    $originalInfo = $_SESSION['logado'];
    unset($_SESSION['logado']);
    session_regenerate_id(); //faz com que a cada requisição, o nosso id de sessão (do cookie) mude. É uma medida de segurança para que alguem não "sequestre" nosso id
    $_SESSION['logado'] = $originalInfo;
}

$isLoginRoute = $pathInfo === '/login';
if (!array_key_exists('logado', $_SESSION) && !$isLoginRoute) { //se o cara não estiver logado e eu não estiver tentando mandar ele para a página de login, redireciona ele para a página de login
    header('Location: /login');
    return;
}

$key = "$httpMethod|$pathInfo";
if (array_key_exists($key, $routes)) {
    $controllerClass = $routes["$httpMethod|$pathInfo"];

    $controller = new $controllerClass($videoRepository);
} else {
    $controller = new Error404Controller();
}
/** @var Controller $controller */
$controller->processaRequisicao();
