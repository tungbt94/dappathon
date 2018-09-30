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


/**
 * \Phanbook\Auth\Auth
 *
 * Manages Authentication/Identity Management in Phanbook
 *
 * @property \Phalcon\Config $config
 * @package Phanbook\Auth
 */
class Authorization
{
    const PUBLIC_SCOPES = 'auth/*, index/*, article/*, test/*, film/*';


    /**
     *
     */
    protected $scopes;


    /**
     * Auth constructor.
     *
     * @param $authorization
     */
    public function __construct($scopes)
    {
        $this->scopes = is_string($scopes)
            ? explode(',', str_replace(' ', '', $scopes))
            : $scopes;
    }


    public static function publicAuthorization()
    {
        return new Authorization(static::PUBLIC_SCOPES);
    }


    /**
     * @return array
     */
    public function getScopes()
    {
        return is_string($this->scopes) ? explode(',', $this->scopes) : (array)$this->scopes;
    }


    /**
     * @param Authorization $authorization
     *
     * @return Authorization
     */
    public function merge($authorization)
    {
        $this->scopes = array_merge($this->getScopes(), $authorization->getScopes());
        return $this;
    }


    /**
     * @param $scopes
     * @param $resourceName
     * @param string $access
     *
     * @return bool
     */
    public function isAllowed($resourceName, $access)
    {
        $acceptScope0 = '*';
        $acceptScope1 = $resourceName . '/*';
        $acceptScope2 = $resourceName . '/' . $access;
        if (in_array($acceptScope0, $this->scopes) || in_array($acceptScope1, $this->scopes) || in_array($acceptScope2, $this->scopes)) {
            return true;
        }

        return false;
    }


}
