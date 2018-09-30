<?php
/**
 * Created by PhpStorm.
 * User: leth
 * Date: 4/23/18
 * Time: 8:22 PM
 */

use Phalcon\Mvc\Router;


return function () use ($application) {

    $router = new Router();


    foreach ($application->getModules() as $module) {
        $routing = $module['routing'];

        if (file_exists($routing)) {
            $group = include $routing;

            if ($group instanceof Router\GroupInterface) {
                $router->mount($group);
            }
        }
    }


    $router->removeExtraSlashes(true);

    return $router;
};