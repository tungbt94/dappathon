<?php namespace Common\Sql\Dao;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/13/2017
 * Time: 3:49 PM
 */
interface DaoInterface
{

    public function createList(array $data, $fields = null);


    public function deleteConditions($params);


    public function updateConditions(array $data, $params);


    public function getModel();


    public function setModel($model);

}