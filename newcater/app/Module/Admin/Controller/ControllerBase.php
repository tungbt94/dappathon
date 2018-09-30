<?php

namespace Module\Admin\Controller;

use Common\Controller\Base;
use Common\Util\Module as Module;
use Model\User;

class ControllerBase extends Base
{
    /** @var  User */
    public $user_info;

    /**
     * @return User
     */
    public function getAuth()
    {
        return $this->session->get("user_info");
    }

    public function getUserInfo()
    {
        if (!empty($this->getAuth())) {
            $auth = $this->getAuth();
            $uinfo = User::findFirst($auth->id);
            $this->view->uinfo = $uinfo;
            return $uinfo;
        } else return null;
    }

    public function setAuth($user)
    {
        $this->session->set("user_info", $user);
        return $this;
    }
}
