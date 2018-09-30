<?php

namespace Module\Client;

use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
    }

    /**
     * Registers the module-only services
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /** @var Config $config */
        $config = $di->get('config');

        $config->offsetSet('module', parse_ini_file(__DIR__ . '/module.ini', true) ?: []);

        /**
         * Setting up the view component
         */
        $di['view'] = function () {
            return provider(
                'base_view',
                [path_from('view:dir'), path_from('view:cacheDir')]
            );
        };

        /**
         * Setting up the view component
         */
        $di['logger'] = function ($realm) {
            $filePath = sprintf('%s/%s.%slog', path_from('log:dir'), date('d-m-y'), $realm ? "$realm." : '');
            return provider(
                'base_logger',
                [$filePath]
            );
        };
    }
}
