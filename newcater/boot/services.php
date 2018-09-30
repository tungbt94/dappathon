<?php
/**
 * Services are globally registered in this file
 */

use Common\Event\AccessListener;
use Phalcon\Mvc\Dispatcher;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;


/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();
$config = require __DIR__ . '/config.php';

/**
 * Register config
 */
$di['config'] = $config;

/**
 * Registering a router
 */
$di['router'] = include __DIR__ . '/router.php';

/**
 * Registering a router
 */
$di['modules'] = [];

/**
 * Start the session the first time some component request the session service
 */
$di['session'] = function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
};

$di['flash'] = function () {
    $flash = new \Phalcon\Flash\Session([
        'error'   => 'alert alert-danger font-alert',
        'success' => 'alert alert-success font-alert',
        'notice'  => 'alert alert-info font-alert',
        'warning' => 'alert alert-warning font-alert'
    ]);
    return $flash;
};

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di['db'] = function () use ($config) {
    $adapter = new DbAdapter(
        [
            "host"     => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname"   => $config->database->dbname,
            "charset"  => $config->database->charset,
        ]
    );

    $eventManager = new EventsManager();
    $eventManager->attach('db', new \Common\Event\DbListener());
    $adapter->setEventsManager($eventManager);

    return $adapter;
};


$di['base_logger'] = function ($file) {
    $logLevels = [
        'emergency' => Phalcon\Logger::EMERGENCY,
        'emergence' => Phalcon\Logger::EMERGENCE,
        'critical'  => Phalcon\Logger::CRITICAL,
        'alert'     => Phalcon\Logger::ALERT,
        'error'     => Phalcon\Logger::ERROR,
        'warning'   => Phalcon\Logger::WARNING,
        'notice'    => Phalcon\Logger::NOTICE,
        'info'      => Phalcon\Logger::INFO,
        'debug'     => Phalcon\Logger::DEBUG,
        'custom'    => Phalcon\Logger::CUSTOM,
        'special'   => Phalcon\Logger::SPECIAL,
    ];

    $logger = new \Phalcon\Logger\Adapter\File($file);
    $logLevel = config('log:level', 'info');
    !isset($logLevels[$logLevel]) && $logLevel = 'info';

    $logger->setLogLevel($logLevels[$logLevel]);
    return $logger;
};


$di['view'] = View::class;

/**
 * Setting up the view component
 */
$di['base_view'] = function ($viewsDir, $cacheDir) {

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($viewsDir);

    $view->registerEngines([
        '.volt'  => function ($view) use ($cacheDir) {

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath'      => $cacheDir,
                'compiledSeparator' => '_',
                'stat'              => true,
                'compileAlways'     => true
            ]);

            $compiler = $volt->getCompiler();
            $compiler->addFunction('in_array', 'in_array');
            $compiler->addFunction('number_format', 'number_format');
            $compiler->addFunction('str_replace', 'str_replace');
            $compiler->addFunction('ucfirst', 'ucfirst');
            $compiler->addFunction('strtotime', 'strtotime');
            $compiler->addFunction('substr ', 'substr ');
            $compiler->addFunction('json_decode ', 'json_decode ');
            $compiler->addFunction('subWords', function ($resolvedArgs, $exprArgs) {
                return 'Helper::subWords(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('minutes_ago', function ($resolvedArgs, $exprArgs) {
                return 'Helper::minutes_ago(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('strlen',
                function ($resolvedArgs, $exprArgs) use ($compiler) {

                    $string = $compiler->expression($exprArgs[0]['expr']);

                    $secondArgument = $compiler->expression($exprArgs[1]['expr']);

                    return 'substr(' . $string . ', 0 ,' . $secondArgument . ')';
                });

            return $volt;
        },
        '.phtml' => PhpEngine::class
    ]);

    return $view;
};


$di['exceptionHandler'] = \Common\Exception\ExceptionHandler::class;


$di['dispatcher'] = function () {

    $events_manager = new EventsManager();

//    $events_manager->attach('dispatch', new AccessListener());

    // Attach a listener
    $events_manager->attach(
        "dispatch:beforeException",
        function (Event $event, \Phalcon\Dispatcher $dispatcher, Exception $exception) {
            $dispatcher->getDI()->get('exceptionHandler')->handle($exception);
        }
    );

    $dispatcher = new Dispatcher();

    // Bind the EventsManager to the dispatcher
    $dispatcher->setEventsManager($events_manager);

    return $dispatcher;
};


$di['authManager'] = \Common\Auth\Manager::class;


$di['filter'] = \Common\Validation\Filter::class;


$di['cache'] = function () use ($config) {
    $frontEnd = new \Phalcon\Cache\Frontend\Data((array)$config->cache);
    $backend = $config->cache->adapter;
    return new $backend ($frontEnd, (array)$config->cache);
};


return $di;