<?php
//cria os objetos apenas quando eles serem necessários, é o conceito de lazy loading, ou seja, podemos ter vários objetos complexos e pesados
$builder = new \DI\ContainerBuilder(); //implementação da PSR 11
$builder->addDefinitions([
    PDO::class => function (): PDO { //padrão factory para criação de objetos
        $dbPath = __DIR__ . '/../banco.sqlite';
        return new PDO("sqlite:$dbPath");
    }
]);

/* @var \Psr\Container\ContainerInterface $container */
$container = $builder->build();

return $container;