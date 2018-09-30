<?php


use Phalcon\Mvc\Router\Group as RouterGroup;

$routing = new RouterGroup([
    'module' => 'client',
    'controller' => 'index',
    'action' => 'index',
    'namespace' => 'Module\Client\Controller',
]);


$routing->add('/:controller/:action/:params', [
    'controller' => 1,
    'action' => 2,
    'params' => 3,
]);

$routing->add('/:controller/:action', [
    'controller' => 1,
    'action' => 2,
]);

$routing->add('/:controller', [
    'controller' => 1,
]);


$routing->add('[/]?', [
    'controller' => 'index',
]);


$routing->add('/auth', [
    'controller' => 'auth',
    'namespace' => 'Module\User\Controller',
    'module' => 'user'
]);


$routing->add('/{slug}-p{id:[0-9]+}', [
    'controller' => 'project',
    'action' => 'index',
    'id' => 3
]);

$routing->add('/{slug}-b{id:[0-9]+}', [
    'controller' => 'project',
    'action' => 'bid',
    'id' => 3
]);

$routing->add('/{slug}-cl{id:[0-9]+}', [
    'controller' => 'project',
    'action' => 'collect',
    'id' => 3
]);

$routing->add('/{slug}-a{id:[0-9]+}', [
    'controller' => 'article',
    'action' => 'index',
    'id' => 3
]);

$routing->add('/ban-chay', [
    'controller' => 'product',
    'action' => 'sale',
    'id' => 3
]);


$routing->add('/tim-kiem', [
    'controller' => 'product',
    'action' => 'search',
    'id' => 3
]);

$routing->add('/{slug}-c{id:[0-9]+}', [
    'controller' => 'category',
    'action' => 'index',
    'id' => 3
]);

$routing->add('/not-found', [
    'controller' => 'error',
    'action' => 'notfound',
]);


$routing->add('/notfound', [
    'controller' => 'error',
    'action' => 'notfound',
]);


$routing->add('/error', [
    'controller' => 'error',
    'action' => 'error',
]);


return $routing;
