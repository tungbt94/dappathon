<?php

namespace Module\Client\Controller;


use Phalcon\Mvc\Controller;

class ErrorController extends Controller
{

    public function notfoundAction()
    {
        $this->view->setMainView('error');
    }


    public function errorAction()
    {
        $this->view->setMainView('error');
    }

}