<?php
/**
 * Created by PhpStorm.
 * User: lemin
 * Date: 7/19/2018
 * Time: 3:21 AM
 */

namespace Module\Client\Controller;


use Common\Ext\Logger;
use Common\Util\Helper;
use Model\BaseModel;
use Model\Project;
use Model\User;

class UserController extends ControllerBase
{
    use Logger;

    public function initialize()
    {
        parent::initialize();
        if (!$this->getAuth()) {
            return $this->response->redirect('/');
        }

    }

    /**
     * @throws \Exception
     */
    public function indexAction()
    {
        $limit = 10;
        $p = $this->request->get("p");
        $p = is_int(intval($p)) ? $p : 1;
        if ($p <= 1) $p = 1;
        $cp = ($p - 1) * $limit;

        $query_submited = "id > :i: and usercreate = :usercreate:";
        $bind_submited = ['i' => 0, 'usercreate' => $this->getAuth()->id];

        $list_submited = Project::find([
            "conditions" => $query_submited,
            "bind" => $bind_submited,
            "limit" => $limit,
            "offset" => $cp,
            "order" => "id desc"
        ]);

        $total = Project::count([
            "conditions" => $query_submited,
            "bind" => $bind_submited
        ]);
        $from = $cp <= 0 ? 1 : $cp;
        $to = $from == 1 ? ($cp + $limit) : ($from + $limit);
        $to = $to > $total ? $total : $to;

        $paging_info = Helper::paginginfo($total, $limit, $p);

        $this->view->setVars(compact('list_submited', 'total', 'paging_info', 'from', 'to', 'object'));
    }

    public function project_submitAction(){
        $limit = 10;
        $p = $this->request->get("p");
        $p = is_int(intval($p)) ? $p : 1;
        if ($p <= 1) $p = 1;
        $cp = ($p - 1) * $limit;

        $query_submited = "id > :i: and usercreate = :usercreate:";
        $bind_submited = ['i' => 0, 'usercreate' => $this->getAuth()->id];

        $list_submited = Project::find([
            "conditions" => $query_submited,
            "bind" => $bind_submited,
            "limit" => $limit,
            "offset" => $cp,
            "order" => "id desc"
        ]);

        $total = Project::count([
            "conditions" => $query_submited,
            "bind" => $bind_submited
        ]);
        $from = $cp <= 0 ? 1 : $cp;
        $to = $from == 1 ? ($cp + $limit) : ($from + $limit);
        $to = $to > $total ? $total : $to;

        $paging_info = Helper::paginginfo($total, $limit, $p);

        $this->view->setVars(compact('list_submited', 'total', 'paging_info', 'from', 'to', 'object'));
    }

    public function project_donatedAction(){

    }
}