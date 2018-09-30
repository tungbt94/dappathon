<?php namespace Model;

use Common\Util\Helper;

/**
 * Created by PhpStorm.
 * User: Dat PC
 * Date: 4/18/2018
 * Time: 3:58 PM
 */
class Category extends BaseModel
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $status;
    /**
     * @var integer
     */
    public $datecreate;
    /**
     * @var integer
     */
    public $usercreate;

    public $avatar;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('usercreate', User::class, 'id', ['alias' => 'User']);
    }

    public function getLink()
    {
        global $config;
        return $config['media']['host'] . Helper::Cleanurl(Helper::khongdau($this->name)) . '-c' . $this->id;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'category';
    }

    public function beforeCreate()
    {
        $this->datecreate = time();
        if (!isset($this->status)) $this->status = BaseModel::STATUS_ACTIVE;
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