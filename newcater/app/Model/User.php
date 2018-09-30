<?php

namespace Model;


/**
 * Class User
 *
 * @package Model
 */
class User extends BaseModel
{

    /**
     * @var integer
     */
    public $id;
    public $username;
    public $password;
    public $status;
    public $email;
    public $datecreate;
    public $role;
    public $fullname;
    public $avatar;
    public $eth_address;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('usercreate', User::class, 'id', ['alias' => 'Usercreate']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return User[]
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
     * @return User
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function getAddress()
    {
        $start = substr($this->eth_address, 0, 4);
        $end = substr($this->eth_address, -4);
        $address = $start . "..." . $end;
        return $address;
    }

}
