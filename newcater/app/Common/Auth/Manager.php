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

namespace Common\Auth;

use Common\Auth\Handler\Factory;
use Common\Auth\Handler\Handler;
use Common\Exception\AuthFailedException;
use Common\Service\User;
use Module\User\Service\RoleService;
use Phalcon\Mvc\User\Component;

/**
 * \Phanbook\Auth\Auth
 *
 * Manages Authentication/Identity Management in Phanbook
 *
 * @property \Phalcon\Config $config
 * @package Phanbook\Auth
 */
class Manager extends Component
{

    /**
     * @var int
     */
    protected $cookieLifetime;


    /**
     * @var User
     */
    protected $userService;


    /**
     * Auth constructor.
     *
     * @param null $cookieLifetime
     */
    public function __construct($cookieLifetime = null)
    {
        $this->userService = $this->di->get(User::class, ['user', 'user', true]);

        if ($cookieLifetime === null) {
            $cookieLifetime = $this->config->get('application')->cookieLifetime;
        }

        $this->cookieLifetime = $cookieLifetime;
    }


    /**
     * Performs an authentication attempt.
     *
     * @param  mixed $credentials
     *
     * @param string $type
     * @param bool $saveSession
     *
     * @return Authentication
     * @throws AuthFailedException
     */
    public function authenticate($credentials, $type = 'password', $saveSession = true)
    {
        $authData = [
            'userAgent'        => $this->request->getClientAddress(true),
            'ipAddress'        => $this->request->getUserAgent(),
            'authenticateTime' => time(),
        ];

        try {
            /** @var Handler $handler */
            $handler = Factory::getHandler($type);
            if ($handler == null) {
                throw new AuthFailedException("Handler type: $type is not valid or not supported");
            }
            $user = $handler->handle($credentials);
            $this->di->getShared('eventsManager')->fire('user:successLogin', $this, $authData);

//      // Check if the remember me was selected
//        if (isset($credentials['remember'])) {
//            $authData['isRememberMe'] = true;
//        }
            $roleService = $this->di->get(RoleService::class, ['role', 'role', true]);
            $roles = $roleService->getPermissionsOfUser($user['id']);

            $authData['info'] = $user;
            $authData['id'] = $user['id'];
            $authData['roles'] = $roles;

            $auth = new Authentication($authData);
            if ($saveSession) {
                $this->saveSession($auth);
            }

            return $auth;

        } catch (AuthFailedException $e) {
            $this->di->getShared('eventsManager')->fire('user:failedLogin', $this, $authData);
            throw $e;
        }
    }


    /**
     * @param $authentication Authentication
     *
     * @return Authorization
     */
    public function authorize($authentication)
    {
        if ($authentication instanceof Authentication) {

            return new Authorization($authentication->getRoles());

        } else {
            return Authorization::publicAuthorization();
        }
    }


    /**
     * @return bool
     */
    public function expireSession()
    {
        if ($this->isAuthorizedVisitor()) {
            return $this->session->destroy();
        }

        return false;
    }


    /**
     * Check whether the user is authorized.
     *
     * @return bool
     */
    public function isAuthorizedVisitor()
    {
        return $this->session->has('auth');
    }


    public function getAuthentication()
    {
        if ($this->isAuthorizedVisitor()) {
            $auth = $this->session->get('auth');
            return new Authentication($auth);
        }

        return null;
    }


    /**
     * Save user session.
     *
     * @param Authentication $object
     */
    public function saveSession($object)
    {
        $this->session->set('auth', $object->toArray());
    }
}

