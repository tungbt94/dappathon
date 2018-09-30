<?php
/**
 * Created by PhpStorm.
 * User: Dat PC
 * Date: 7/22/2018
 * Time: 12:51 AM
 */

namespace Module\Admin\Controller;

use Common\Util\Helper;
use Common\Util\Pql;
use Model\BaseModel;
use Model\Category;
use Model\Withdraw;

class WithdrawController extends ControllerInit
{
    public function initialize()
    {
        $this->view->active_sidebar = "withdraw/index";
        parent::initialize();
        $this->view->header = [
            'title' => 'Withdraw',
            'description' => 'Withdraw',
            'keyword' => 'Withdraw'
        ];
    }

    public function indexAction()
    {
        $limit = 20;
        $query = "id > :s: ";
        $bind['s'] = 0;
        $q = $this->request->get("q");

        $status = $this->request->get('status');
        if ($status >= 0 && strlen($status) > 0) {
            $query .= " and status_display = :s:";
            $bind['s'] = $status;
            $this->view->status_display = $status;
        }

        $p = $this->request->get("p");
        if ($p <= 1) $p = 1;
        $cp = ($p - 1) * $limit;
        $list_data = Withdraw::find([
            "conditions" => $query,
            "bind" => $bind,
            "limit" => $limit,
            "offset" => $cp,
            "order" => "id desc"
        ]);
        $count = Withdraw::count([
            "conditions" => $query,
            "bind" => $bind
        ]);

        $paging_info = Helper::paginginfo($count, $limit, $p);

        $this->view->setVars([
            'paging_info' => $paging_info,
            'list_data' => $list_data,
            'data_get' => $this->request->get(),
        ]);
    }

    public function formAction()
    {

        $id = $this->request->get("id");
        if (!$id) {
            $this->flash->error('Withdrawal Not Found');
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $object = Withdraw::findFirst($id);

        if (!$object || $object->id != $id) {
            $this->flash->error('Withdrawal Not Found');
            return $this->response->redirect($this->request->getHTTPReferer());
        }


        if ($this->request->isPost()) {

            try {
                $data_post = $this->getPost("status, vote_start_time, vote_end_time");
                $data_post['vote_start_time'] = Helper::process_date_form($data_post['vote_start_time']);
                $data_post['vote_end_time'] = Helper::process_date_form($data_post['vote_end_time']);


                $object->map_object($data_post);
                // </editor-fold>


                if ($object->save()) $this->flash->success("Success");
                else $this->flash->error("Something went wrong");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        !empty($id) && $o = Withdraw::findFirst($id);
        $this->view->setVars([
            'object' => $object
        ]);
    }

    public function updateAction()
    {
        $id = $this->request->get("id");

        if (!$id) {
            $this->flash->error('Withdraw not found');
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($this->request->isPost()) {

            try {
                $data_post = $this->getPost('status_display');

                // <editor-fold desc="Validate">
                if ($id > 0) {
                    $o = Withdraw::findFirst($id);
                } else {
                    $o = new Withdraw();
                }
                $o->map_object($data_post);
                // </editor-fold>

                if ($o->save()) $this->flash->success("Success");
                else $this->flash->error("Something went wrong");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        !empty($id) && $o = Withdraw::findFirst($id);
        $this->view->setVars([
            'object' => $o
        ]);
    }

    public function deleteAction()
    {
        $id = $this->request->get("id");
        $o = Withdraw::findFirst($id);
        if ($o) {
            try {
                $o->delete();
                $this->flash->success("Thao tác thành công!");
            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }
        $this->response->redirect($this->request->getHTTPReferer());
    }
}