<?php

use Phalcon\Mvc\Application;

//ini_set('display_errors', 1); error_reporting(E_ALL);
//ini_set('display_errors', 1); error_reporting(E_ERROR);
try {

    /**
     * Handle the request
     */
    $application = new Application();


    /**
     * Include modules
     */
    require __DIR__ . '/../boot/loader.php';


    /**
     * Include services
     */
    $di = require __DIR__ . '/../boot/services.php';


    /**
     * Assign the DI
     */
    $application->setDI($di);


    echo $application->handle()->getContent();

} catch (\Phalcon\Di\Exception $e) {
    echo $e->getMessage();
}
