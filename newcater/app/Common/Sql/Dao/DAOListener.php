<?php namespace Common\Sql\Dao;

use Phalcon\Events\Event;

/**
 * Created by PhpStorm.
 * User: balol
 * Date: 4/2/2017
 * Time: 11:42 PM
 */
interface DAOListener
{

    function beforeUpdate(Event $event, $obj, $data);

    function beforeCreate(Event $event, $obj, $data);

    function beforeFindById(Event $event, $obj, $data);

    function beforeFindFirst(Event $event, $obj, $data);

    function beforeFindByProps(Event $event, $obj, $data);

    function beforeDeleteById(Event $event, $obj, $data);


    function afterUpdate(Event $event, $obj, $data);

    function afterCreate(Event $event, $obj, $data);

    function afterFindById(Event $event, $obj, $data);

    function afterFindFirst(Event $event, $obj, $data);

    function afterFindByProps(Event $event, $obj, $data);

    function afterDeleteById(Event $event, $obj, $data);

}