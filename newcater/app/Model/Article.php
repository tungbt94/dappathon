<?php
/**
 * Created by PhpStorm.
 * User: lemin
 * Date: 7/2/2018
 * Time: 6:16 AM
 */

namespace Model;


use Common\Util\Helper;

class Article extends BaseModel
{
    const TYPE_NORMAL = 1;
    const TYPE_PRODUCT = 2;
    public $id;
    public $name;
    public $avatar;
    public $category_id;
    public $description;
    public $status;
    public $content;
    public $datecreate;
    public $usercreate;
    public $seo_title;
    public $seo_description;
    public $seo_keyword;
    public $type;



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
        return $config['media']['host'] . Helper::Cleanurl(Helper::khongdau($this->name)) . '-a' . $this->id;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'article';
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