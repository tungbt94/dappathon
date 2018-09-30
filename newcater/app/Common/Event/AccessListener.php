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

use Common\Auth\Manager;
use Common\Exception\AuthFailedException;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Event;

/**
 * \Phanbook\Common\Library\Events\AccessListener
 *
 * @package Phanbook\Common\Library\Events
 */
class AccessListener extends AbstractEvent
{
    /**
     * This action is executed before execute any action in the application.
     *
     * @param Event $event Event object.
     * @param Dispatcher $dispatcher Dispatcher object.
     * @param array $data The event data.
     *
     * @return mixed
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher, array $data = null)
    {
        if ($dispatcher->getModuleName() != 'admin') {
            return;
        }

        /** @var Manager $authManager */
        $authManager = $this->getDI()->getShared('authManager');

        $authentication = $authManager->getAuthentication();

        if ($authentication) {
            $this->getDI()->set('authentication', $authentication);
        }

        $authorization = $authManager->authorize($authentication);

        $grantPermission = $authorization->isAllowed($dispatcher->getControllerName(), $dispatcher->getActionName());

        if (!$grantPermission) {

            $authFailedCode = $authentication ? AuthFailedException::PERMISSION_DENIED_CODE : AuthFailedException::NOT_LOGIN_YET_CODE;

            $dispatcher->getEventsManager()->fire('dispatch:beforeException',
                $dispatcher,
                new AuthFailedException("Không được truy cập", $authFailedCode));
            $event->stop();
        }

        return !$event->isStopped();
    }
}