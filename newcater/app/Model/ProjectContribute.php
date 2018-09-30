<?php
/**
 * Created by PhpStorm.
 * User: Dat PC
 * Date: 7/21/2018
 * Time: 12:44 PM
 */

namespace Model;


class ProjectContribute extends BaseModel
{
    public $id;
    public $project_id;
    public $user_id;
    public $project_address;
    public $contribute_address;
    public $datecreate;
    public $hash;
    public $value;


    public function beforeCreate()
    {
        $this->datecreate = time();
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'project_contribute';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return self[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return self
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}