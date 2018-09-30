<?php
/**
 * Created by PhpStorm.
 * User: Dat PC
 * Date: 7/22/2018
 * Time: 12:19 AM
 */

namespace Model;


class Withdraw extends BaseModel
{
    public $id;
    public $amount;
    public $project_id;
    public $caption;
    public $datecreate;
    public $usercreate;
    public $status;
    public $hash;
    public $vote_start_time;
    public $vote_end_time;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('usercreate', User::class, 'id', ['alias' => 'User']);
        $this->belongsTo('project_id', Project::class, 'id', ['alias' => 'Project']);
    }

    public function beforeCreate()
    {
        $this->datecreate = time();
        $this->status = BaseModel::STATUS_APPROVE;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'withdraw';
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