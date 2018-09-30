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

use Common\Util\Classes;

/**
 * \Phanbook\Auth\Auth
 *
 * Manages Authentication/Identity Management in Phanbook
 *
 * @property \Phalcon\Config $config
 * @package Phanbook\Auth
 */
class Authentication
{

    protected $id;


    protected $userAgent;


    protected $ipAddress;


    protected $info;


    protected $roles;

    protected $authenticateTime;


    public function __construct($authResult)
    {
        Classes::mapObject($authResult, $this);
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return mixed
     */
    public function getAuthenticatedTime()
    {
        return $this->authenticatedTime;
    }


    function toArray()
    {
        $data = get_object_vars($this);
        return array_from($data) ?: $data;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
