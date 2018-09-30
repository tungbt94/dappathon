<?php

use Phalcon\Config;
use Dotenv\Dotenv;
(new Dotenv(BASE_PATH))->load();

return new Config(
    [
        'app' => [
            'url'    => $_ENV['APP_URL'] ?: 'http://localhost',
            'dev'    => $_ENV['APP_DEV'] == 'true' ? true : false,
            'id_url' => $_ENV['APP_ID_URL'] ?: 'http://id-localhost'
        ],

        'cache' => [
            'adapter'  => '\Phalcon\Cache\Backend\File',
            'cacheDir' => BASE_PATH . '/storage/cache/',
            'lifetime' => 86400
        ],

        'database' => [
            'adapter'  => 'Mysql',
            'host'     => $_ENV['MYSQL_HOST'] ?: 'localhost',
            'username' => $_ENV['MYSQL_USERNAME'] ?: 'root',
            'password' => $_ENV['MYSQL_PASSWORD'] ?: 'secret',
            'dbname'   => $_ENV['MYSQL_DBNAME'] ?: 'test',
            'charset'  => $_ENV['MYSQL_CHARSET'] ?: 'utf8',
            'port'     => $_ENV['MYSQL_PORT'] ?: '3306',
        ],

        'google' => [
            'client_id'     => $_ENV['GOOGLE_CLIENT_ID'],
            'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            'recaptcha' => [
                'key' => $_ENV['GOOGLE_RECAPTCHA_KEY'],
                'secret' => $_ENV['GOOGLE_RECAPTCHA_SECRET']
            ]
        ],

        'media' => [
            'host' => $_ENV['APP_URL'] ?: 'http://localhost',
            'dir' => $_ENV['APP_DIR']
        ],

        'mail' => [
            'host'       => $_ENV['MAIL_HOST'],
            'port'       => $_ENV['MAIL_PORT'],
            'encryption' => $_ENV['MAIL_ENCRYPTION'],
            'username'   => $_ENV['MAIL_USERNAME'],
            'password'   => $_ENV['MAIL_PASSWORD'],
        ],
        'api' => [
            'url' => $_ENV['API_URL'],
            'api_key' => $_ENV['API_KEY'],
        ]
    ]
);