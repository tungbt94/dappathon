<?php
/**
 * Created by PhpStorm.
 * User: Dat PC
 * Date: 7/22/2018
 * Time: 5:40 AM
 */

namespace Model;


class UserApprove extends BaseModel
{
    public $id;
    public $user_id;
    public $project_id;
    public $withdraw_id;
    public $hash;
    public $datecreate;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('user_id', User::class, 'id', ['alias' => 'User']);
        $this->belongsTo('project_id', Project::class, 'id', ['alias' => 'Project']);
    }

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
        return 'user_approve';
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