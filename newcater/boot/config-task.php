<?php


use Phalcon\Config;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
(new Dotenv(BASE_PATH))->load();

return new Config([
    'database' => [
        'adapter'  => 'Mysql',
        'host'     => $_ENV['MYSQL_HOST'] ?: 'localhost',
        'username' => $_ENV['MYSQL_USERNAME'] ?: 'root',
        'password' => $_ENV['MYSQL_PASSWORD'] ?: 'secret',
        'dbname'   => $_ENV['MYSQL_DBNAME'] ?: 'test',
        'charset'  => $_ENV['MYSQL_CHARSET'] ?: 'utf8',
        'port'     => $_ENV['MYSQL_PORT'] ?: '3306',
    ],
    'api' => [
        'url' => $_ENV['API_URL'],
        'api_key' => $_ENV['API_KEY'],
    ]
]);