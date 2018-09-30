<?php

namespace Common\Ext;


/**
 * Trait AuthExt
 *
 * @method  array _user()
 * @method  string _userId()
 * @method  string _userFullname()
 * @method  string _userEmail()
 *
 */
trait Auth
{

    protected $isInit = false;

    /**
     * @var \Common\Auth\Authentication
     */
    protected $authentication;


    /**
     * @var \Common\Auth\Manager
     */
    protected $authManager;


    /**
     * @var array
     */
    protected $userInfo;


    function __call($name, $ags)
    {
        if (substr($name, 0, 5) == '_user') {

            return $this->_getUserAttribute(substr($name, 5));
        }

        if (method_exists(parent::class, '__call')) {
            return parent::__call($name, $ags);
        }
    }


    protected function _getUserAttribute($name)
    {
        $userInfo = $this->userInfo ?: $this->getUserInfo();

        if ($userInfo == null) {
            return false;
        }

        if ($name) {
            $name = under_score($name);
            if (!isset($userInfo[$name])) {
                return false;
            }
        }

        return $name ? $userInfo[$name] : $userInfo;
    }


    protected function getUserInfo()
    {
        $this->_init();
        return $this->userInfo;
    }


    protected function isAuthorized()
    {
        $this->_init();
        return $this->authManager->isAuthorizedVisitor();
    }


    function initUserInfo()
    {
        $this->_init(true);
    }


    /**
     * @param $resfresh
     */
    private function _init($resfresh = false)
    {
        if ($this->isInit && !$resfresh) {
            return;
        }

        if ($this->authManager == null) {
            $this->authManager = provider('authManager');
        }

        if ($this->authentication == null) {
            $this->authentication = provider('authentication') ?: $this->authManager->getAuthentication();
        }

        if ($this->userInfo == null) {
            $this->userInfo = $this->authentication ? $this->authentication->getInfo() : [];
        }

        $this->isInit = true;
    }


    protected function _authManager()
    {
        $this->_init();
        return $this->authManager;
    }


    protected function _auth()
    {
        $this->_init();
        return $this->authentication;
    }


    protected function _userInfo()
    {
        $this->_init();
        return $this->userInfo;
    }
}