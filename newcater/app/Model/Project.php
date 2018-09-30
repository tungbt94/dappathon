<?php
/**
 * Created by PhpStorm.
 * User: lemin
 * Date: 7/2/2018
 * Time: 6:16 AM
 */

namespace Model;


use Common\Util\Helper;

class Project extends BaseModel
{

    const STATUS_PENDING = 0;
    const STATUS_RUNNING = 1;
    const STATUS_CLOSED = 2;

    const TYPE_FUNDING = 1;
    const TYPE_AUCTION = 2;
    const TYPE_COLLECT = 3;

    public $id;
    public $name;
    public $contact_email;
    public $contact_person;
    public $target;
    public $video;
    public $avatar;
    public $content;
    public $status;
    public $status_display;
    public $contribute_start_time;
    public $contribute_end_time;
    public $datecreate;
    public $usercreate;
    public $category_id;
    public $description;
    public $raised;
    public $percent_approve;
    public $contribute_address;
    public $funding_receipt;
    public $balance;
    public $hash;
    public $percent_terminate;
    public $percent_consensus;
    public $type;
    public $highest_value;
    public $highest_address;
    public $winner_user_id;
    public $min_value;
    public $step_value;
    public $token_name;
    public $symbol;
    public $total_supply;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('usercreate', User::class, 'id', ['alias' => 'User']);
        $this->belongsTo('category_id', Category::class, 'id', ['alias' => 'Category']);
    }

    public function getLink()
    {
        global $config;
        if ($this->type == self::TYPE_FUNDING) {
            $url = $config['media']['host'] . Helper::Cleanurl(Helper::khongdau($this->name)) . '-p' . $this->id;
        } else if ($this->type == self::TYPE_COLLECT) {
            $url = $config['media']['host'] . Helper::Cleanurl(Helper::khongdau($this->name)) . '-cl' . $this->id;
        } else {
            $url = $config['media']['host'] . Helper::Cleanurl(Helper::khongdau($this->name)) . '-b' . $this->id;
        }

        return $url;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'project';
    }

    public function beforeCreate()
    {
        $this->datecreate = time();
        $this->raised = 0;
        if (!isset($this->status)) $this->status = self::STATUS_PENDING;
        if (!isset($this->status_display)) $this->status_display = BaseModel::STATUS_INACTIVE;
        if (!isset($this->type)) $this->type = self::TYPE_FUNDING;
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

    public function getStatusText()
    {
        if ($this->status == self::STATUS_PENDING) return "Pending";
        if ($this->status == self::STATUS_RUNNING) return "Running";
        if ($this->status == self::STATUS_REJECT) return "Reject";
        return "Pending";
    }

    public function getStatusClass()
    {
        if ($this->status == self::STATUS_PENDING) return "warning";
        if ($this->status == self::STATUS_RUNNING) return "success";
        if ($this->status == self::STATUS_REJECT) return "danger";
        return "warning";
    }

    public function getStatusDisplayText()
    {

        if ($this->status_display == BaseModel::STATUS_ACTIVE) return "Active";
        else if ($this->status_display == BaseModel::STATUS_INACTIVE) return "Inactive";
    }


    public function getStatusDisplayClass()
    {
        if ($this->status_display == BaseModel::STATUS_ACTIVE) return "success";
        else if ($this->status_display == BaseModel::STATUS_INACTIVE) return "warning";
    }


}