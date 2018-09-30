<?php


use Phalcon\Mvc\Router\Group as RouterGroup;

$routing = new RouterGroup([
    'module'     => 'admin',
    'controller' => 'dashboard',
    'action'     => 'index',
    'namespace'  => 'Module\Admin\Controller',
]);

$routing->setPrefix('/admin');

$routing->add('/:controller/:action/:params', [
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$routing->add('/:controller/:action', [
    'controller' => 1,
    'action'     => 2,
]);

$routing->add('/:controller/:int', [
    'controller' => 1,
    'id'         => 2,
]);

$routing->add('/:controller', [
    'controller' => 1,
]);

$routing->add('[/]?', [
    'controller' => 'dashboard',
]);

$routing->add('/index', [
    'controller' => 'dashboard',
]);

return $routing;
