<?php

$container = $app->getContainer();

$root = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR;

$container['view'] = function () use ( $root ){
    $view = new \Slim\Views\Twig( $root . 'src/Shipping/App/View', [
        'cache' => false
    ] );
    return new \Tdw\Shipping\Infra\Service\TwigView( $view );
};

$container['flash'] = function () {
    return new \Tdw\Shipping\Infra\Service\SlimFlashMessage(
        new \Slim\Flash\Messages()
    );
};

$container['pdo'] = function () {
    try {
        $options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
        $pdo = new \PDO('mysql:host=localhost;dbname=shipping', 'root', 'root', $options);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $pdo;
    } catch ( PDOException $e ) {
        die($e->getMessage());
    }
};

$container['persistenceCarriers'] = function () use ( $container ){
    return new \Tdw\Shipping\Infra\Persistence\Carriers( $container['pdo'] );
};

$container['persistenceCarriersRange'] = function () use ( $container ){
    return new \Tdw\Shipping\Infra\Persistence\CarriersRange( $container['pdo'] );
};

$container['persistenceCarriersRangeSearch'] = function () use ( $container ){
    return new \Tdw\Shipping\Infra\Persistence\CarriersRangeSearch( $container['pdo'] );
};
