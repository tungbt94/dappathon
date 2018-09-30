<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/builtin.php';


defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');


// Basic autoloader
spl_autoload_register(
    function ($className) {
        $filepath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, BASE_PATH . '/app/' . $className . '.php');

        if (file_exists($filepath)) {
            require $filepath;
        }
//        else {
//            throw new Exception("Class not found: " . $className);
//        }
    }
);


$loader = new \Phalcon\Loader();
$loader->registerNamespaces(['Module\Admin\Module']);


/**
 * Register application modules
 */
$application->registerModules(
    [
        'client' => [
            'className' => 'Module\Client\Module',
            'path' => APP_PATH . '/Module/Client/Module.php',
            'routing' => APP_PATH . '/Module/Client/routing.php'
        ],

        'admin' => [
            'className' => 'Module\Admin\Module',
            'path' => APP_PATH . '/Module/Admin/Module.php',
            'routing' => APP_PATH . '/Module/Admin/routing.php'
        ]
    ]
);