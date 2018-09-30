<?php

namespace Common\Ext;

use Phalcon\Logger as PhLogger;

/**
 * Created by PhpStorm.
 * User: leth
 * Date: 4/23/18
 * Time: 10:01 PM
 *
 * @property \Phalcon\Http\Request request
 */
trait Logger
{

    protected function __log($type, $message, $realm = null)
    {
        /** @var PhLogger\Adapter $logger */
        $logger = provider('logger', [$realm]);
        $message = sprintf('%s - %s', __CLASS__, $message);
        switch (strtolower($type)) {
            case 'error':
                $logger->error($message);
                break;
            case 'info':
                $logger->info($message);
                break;
            case 'critical':
                $logger->critical($message);
                break;
            case 'warning':
                $logger->warning($message);
                break;
            case 'notice':
                $logger->notice($message);
                break;
            case 'debug':
                $logger->debug($message);
                break;
            default:

        }
    }


    protected function _logError($message, $realm = null)
    {
        $this->__log('error', $message, $realm);
    }

    protected function _logInfo($message, $realm = null)
    {
        $this->__log('info', $message, $realm);
    }

    protected function _logNotice($message, $realm = null)
    {
        $this->__log('notice', $message, $realm);
    }

    protected function _logWarning($message, $realm = null)
    {
        $this->__log('warning', $message, $realm);
    }

    protected function _logEmergency($message, $realm = null)
    {
        $this->__log('emergency', $message, $realm);
    }

    protected function _logCritical($message, $realm = null)
    {
        $this->__log('critical', $message, $realm);
    }

    protected function _logDebug($message, $realm = null)
    {
        $this->__log('debug', $message, $realm);
    }
}
