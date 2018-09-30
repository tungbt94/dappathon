<?php

namespace Common\Controller;

use Common\Ext\Auth as AuthExt;
use Common\Ext\Controller as ControllerExt;
use Phalcon\Mvc\Controller;

class Base extends Controller
{
    use AuthExt;
    use ControllerExt;
}
