<?php


namespace Common\Auth\Handler;


use Assert\Assertion;
use Phalcon\Mvc\User\Component;

class Password extends Component implements Handler
{
    use PersistentVerifyExt;


    function handle($credentials)
    {
        Assertion::notEmpty($credentials['username'], 'Thiếu thông tin tài khoản xác thực');
        Assertion::notEmpty($credentials['password'], 'Thiếu thông tin tài khoản xác thực');

        $user = $this->verify($credentials['username'], function ($user) use ($credentials) {
            return $this->security->checkHash($credentials['password'], $user['password']);
        });

        return $user;
    }


    static function getName()
    {
        return 'password';
    }
}