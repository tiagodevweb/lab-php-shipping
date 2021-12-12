<?php

if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../../../vendor/autoload.php';

session_start();

$settings = require __DIR__ . '/settings.php';

$app = new \Slim\App($settings);

require __DIR__ . '/dependencies.php';

require __DIR__ . '/routes.php';

$app->run();