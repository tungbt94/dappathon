<?php
/**
 * Created by PhpStorm.
 * User: leth
 * Date: 4/25/18
 * Time: 6:09 AM
 */

namespace Common\Auth\Handler;


use Common\Exception\AuthFailedException;
use Common\Service\User;


trait PersistentVerifyExt
{


    /**
     * @param $id
     * @param $verifyInfo
     *
     * @return array
     */
    function verify($id, $verifyInfo)
    {
        /** @var User $service */
        $service = provider(User::class, ['user', 'user', true]);

        $user = $service->findUserByEmailOrUsername($id);
        if ($user == null) {
            throw AuthFailedException::userNotFound();
        }

        $user = $user->toArray();

        if ($verifyInfo instanceof \Closure) {
            $verified = call_user_func($verifyInfo, $user);

        } elseif (is_array($verifyInfo)) {

            $verified = true;
            foreach ($verifyInfo as $k => $v) {
                if (!isset($user[$k]) || $user[$k] != $v) {
                    $verified = false;
                    break;
                }
            }
        } elseif ($verifyInfo === null) {

            $verified = true;
        } else {

            $verified = ($user == $verifyInfo);
        }

        if ($verified) {
            return $user;
        }

        throw AuthFailedException::userPasswordNotMatched();
    }
}