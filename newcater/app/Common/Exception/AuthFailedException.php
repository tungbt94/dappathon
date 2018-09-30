<?php
/**
 * Created by PhpStorm.
 * User: leth
 * Date: 4/22/18
 * Time: 4:57 AM
 */

namespace Common\Exception;


class AuthFailedException extends Exception
{

    const NOT_LOGIN_YET_CODE = 101;
    const PERMISSION_DENIED_CODE = 102;
    const USER_NOT_FOUND_CODE = 103;


    static function userNotFound()
    {
        return new AuthFailedException("Người dùng không tồn tại", static::USER_NOT_FOUND_CODE);
    }

    static function userPasswordNotMatched()
    {
        return new AuthFailedException("Tài khoản hoặc mật khẩu chưa chính xác");
    }

}