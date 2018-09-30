<?php
/**
 * Phanbook : Delightfully simple forum software
 *
 * Licensed under The BSD License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link    http://phanbook.com Phanbook Project
 * @since   1.0.0
 * @license https://github.com/phanbook/phanbook/blob/master/LICENSE.txt
 */

namespace Common\Event;

use Phalcon\Events\Event;
use Phalcon\Db\Adapter\Pdo;
use Phalcon\Logger\Adapter;

/**
 * \Phanbook\Common\Library\Events\DbListener
 *
 * @package Phanbook\Common\Library\Events
 */
class DbListener extends AbstractEvent
{
    /**
     * Database queries listener.
     *
     * You can disable queries logging by changing log level.
     *
     * @param  Event $event
     * @param  Pdo $connection
     *
     * @return bool
     */
    public function beforeQuery(Event $event, Pdo $connection)
    {
        $string = $connection->getSQLStatement();
        $variables = $connection->getSqlVariables();
        $context = $variables ?: [];

        /** @var Adapter $logger */
        $logger = $this->getDI()->get('logger', ['db']);

        if (!empty($context)) {
            $context = ' [' . implode(', ', $context) . ']';
        } else {
            $context = '';
        }

        $logger->debug($string . $context);

        return true;
    }
}
