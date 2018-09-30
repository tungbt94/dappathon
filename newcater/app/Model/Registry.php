<?php
/**
 * Created by PhpStorm.
 * User: lemin
 * Date: 7/2/2018
 * Time: 8:23 PM
 */

namespace Model;


class Registry extends BaseModel
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $logo;
    public $favicon;
    /**
     * @var string
     */
    public $slogan;
    /**
     * @var string
     */
    public $map;
    /**
     * @var string
     */
    public $link_facebook;
    /**
     * @var string
     */
    public $link_google;
    /**
     * @var string
     */
    public $link_twitter;

    /**
     * @var string
     */
    public $seo_title;
    /**
     * @var string
     */
    public $seo_description;
    /**
     * @var string
     */
    public $seo_keyword;
    /**
     * @var string
     */
    public $hot_line;
    /**
     * @var string
     */
    public $slide;
    public $address;
    public $sale_img;
    public $partner;
    public $script_head;
    public $script_body;
    public $team_support;


    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'registry';
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