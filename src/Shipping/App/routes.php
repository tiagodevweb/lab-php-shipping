<?php

$app->get('/', \Tdw\Shipping\App\Action\Home::class);
$app->get('/pesquisa', \Tdw\Shipping\App\Action\CarriersRangeSearch::class);

$app->get('/transportadoras', \Tdw\Shipping\App\Action\Carriers::class);
$app->post('/transportadoras', \Tdw\Shipping\App\Action\CreateCarriers::class);
$app->get('/transportadoras/editar/{id}', \Tdw\Shipping\App\Action\UpdateCarriers::class);
$app->post('/transportadoras/atualizar', \Tdw\Shipping\App\Action\UpdateCarriers::class . ':post');

$app->get('/regioes', \Tdw\Shipping\App\Action\CarriersRange::class);
$app->post('/regioes', \Tdw\Shipping\App\Action\CreateCarriersRange::class);
$app->get('/regioes/editar/{id}', \Tdw\Shipping\App\Action\UpdateCarriersRange::class);
$app->post('/regioes/atualizar', \Tdw\Shipping\App\Action\UpdateCarriersRange::class . ':post');
$app->get('/regioes/excluir/{id}', \Tdw\Shipping\App\Action\DeleteCarriersRange::class);
