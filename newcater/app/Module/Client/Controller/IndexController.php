<?php

namespace Module\Client\Controller;


use Common\Util\Helper;
use Common\Util\Pql;
use Model\Ads;
use Model\BaseModel;
use Model\Category;
use Model\Contact;
use Model\FeatureFilm;
use Model\Film;
use Model\FilmCategory;
use Model\HomeCategory;
use Model\Project;
use Model\ProjectContribute;

class IndexController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        $list_category = Category::find([
            'conditions' => 'status = :status:',
            'bind' => [
                'status' => BaseModel::STATUS_ACTIVE
            ],
            'order' => 'id desc',
            'limit' => 6
        ]);

        $list_running = Project::find([
            'conditions' => '(contribute_end_time = 0 or contribute_end_time > :contribute_end_time:) and status_display = :status_display: and type = :type:',
            'bind' => [
                'contribute_end_time' => time(),
                'status_display' => BaseModel::STATUS_ACTIVE,
                'type' => Project::TYPE_FUNDING
            ],
            'limit' => 6,
            'order' => 'id desc'
        ]);

        $list_upcoming = Project::find([
            'conditions' => 'contribute_end_time > 0 and contribute_end_time < :contribute_end_time: and status_display = :status_display: and type = :type:',
            'bind' => [
                'contribute_end_time' => time(),
                'status_display' => BaseModel::STATUS_ACTIVE,
                'type' => Project::TYPE_FUNDING
            ],
            'limit' => 6,
            'order' => 'id desc'
        ]);

        $list_auction = Project::find([
            'conditions' => 'type = :type: and status_display = :status_display:',
            'bind' => [
                'type' => Project::TYPE_AUCTION,
                'status_display' => BaseModel::STATUS_ACTIVE
            ],
            'limit' => 8,
            'order' => 'id desc'
        ]);

        $list_collect = Project::find([
            'conditions' => 'type = :type: and status_display = :status_display:',
            'bind' => [
                'type' => Project::TYPE_COLLECT,
                'status_display' => BaseModel::STATUS_ACTIVE
            ],
            'limit' => 8,
            'order' => 'id desc'
        ]);

        $this->view->setVars(compact('list_category', 'list_running', 'list_upcoming', 'list_auction', 'list_collect'));
    }
}

