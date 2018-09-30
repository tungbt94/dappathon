<?php


namespace Common\Auth\Handler;


use Common\Service\User;


abstract class OAuth implements Handler
{
    use PersistentVerifyExt;

    abstract function getLinkLogin($urlCallBack);

}