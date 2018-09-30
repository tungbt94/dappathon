<?php

namespace Module\Admin\Controller;


class DashboardController extends ControllerInit
{
    public function initialize()
    {
        parent::initialize();
        $this->view->header = [
            'title' => 'Dashboard',
            'description' => 'Dashboard',
            'keyword' => 'Dashboard'
        ];
    }

    public function indexAction()
    {

    }


}

