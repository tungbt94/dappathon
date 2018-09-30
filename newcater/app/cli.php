<?php

use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;

ini_set('max_execution_time', 0);
set_time_limit(0);
// Using the CLI factory default services container
$di = new CliDI();
date_default_timezone_set('Asia/Ho_Chi_Minh');
/**
 * Register the autoloader and tell it to register the tasks directory
 */
$loader = new Loader();

$loader->registerDirs(
    [
        __DIR__ . "/task",
        __DIR__ . "/Model",
        __DIR__ . "/Common/Util",
    ]
);


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
$loader->register();



// Load the configuration file (if any)

$configFile = __DIR__ . "/../boot/config-task.php";
if (is_readable($configFile)) {
    $config = include $configFile;
    $di->set("config", $config);
}


/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {
    $dbConfig = $config->database->toArray();
    $adapter = $dbConfig['adapter'];
    unset($dbConfig['adapter']);

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

    return new $class($dbConfig);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

$di->setShared('config', function () use ($config) {
    return $config;
});


// Create a console application
$console = new ConsoleApp();

$console->setDI($di);


/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments["task"] = $arg;
    } elseif ($k === 2) {
        $arguments["action"] = $arg;
    } elseif ($k >= 3) {
        $arguments["params"][] = $arg;
    }
}


try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();

    exit(255);
}