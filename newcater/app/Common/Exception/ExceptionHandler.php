<?php

namespace Common\Exception;

use Common\Ext\Logger;
use Common\Util\Helper;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Component;

/**
 * Created by PhpStorm.
 * User: leth
 * Date: 4/22/18
 * Time: 9:53 AM
 */
class ExceptionHandler extends Component
{
    use Logger;


    /**
     * @param $exception \Exception
     */
    function handle($exception)
    {
        d($exception->getMessage());
        if ($exception instanceof AuthFailedException) {

            switch ($exception->getCode()) {

                case AuthFailedException::PERMISSION_DENIED_CODE:
                    $this->flash->error($exception->getMessage());
                    header('location: /');
                    die;
                    break;

                case AuthFailedException::NOT_LOGIN_YET_CODE:
                    header('location: /admin/auth/login');
                    die;
                    break;

                default:
                    header('location: /');
                    break;
            }

        } elseif ($exception instanceof Exception) {

            $this->flashSession->error($exception->getMessage());
            header('location: /error');

        } elseif ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
            switch ($exception->getCode()) {
                case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                case Dispatcher::EXCEPTION_INVALID_HANDLER:
                case Dispatcher::EXCEPTION_INVALID_PARAMS:
                    header('location: /notfound');
            }

        } else {
            $this->_logError($exception->getMessage(), 'exception');
            $this->flashSession->error("Minh Sun chưa sửa lỗi này :( ");
            header('location: /error');

        }
    }

}