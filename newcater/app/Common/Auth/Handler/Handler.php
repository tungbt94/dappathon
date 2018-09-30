<?php


namespace Common\Auth\Handler;


use Common\Exception\AuthFailedException;

interface Handler
{

    /**
     * @param $credentials
     *
     * @throws AuthFailedException
     * @return mixed
     */
    function handle($credentials);


    static function getName();
}