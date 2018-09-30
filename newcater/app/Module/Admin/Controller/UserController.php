<?php

namespace Module\Admin\Controller;

use Common\Exception\Exception;
use Common\Util\Helper;
use Model\User;

/**
 * Created by PhpStorm.
 * User: Dat PC
 * Date: 4/18/2018
 * Time: 11:14 AM
 */
class UserController extends ControllerInit
{
    public function initialize()
    {
        $this->view->active_sidebar = "user/index";
        parent::initialize();
        $this->view->header = [
            'title' => 'User',
            'description' => 'User',
            'keyword' => 'User'
        ];
    }

    public function indexAction()
    {
        $limit = 20;
        $query = "id > :s: ";
        $bind['s'] = 0;
        $q = $this->request->get("q");
        if (!empty($q)) {
            $query .= " and (usernname like :q: or id = :q: or email like :q:)";
            $bind += ['q' => "%$q%"];
        }

        $status = $this->request->get('status');
        if ($status >= 0 && strlen($status) > 0) {
            $query .= " and status = :s:";
            $bind['s'] = $status;
            $this->view->status = $status;
        }

        $p = $this->request->get("p");
        if ($p <= 1) $p = 1;
        $cp = ($p - 1) * $limit;
        $list_data = User::find([
            "conditions" => $query,
            "bind" => $bind,
            "limit" => $limit,
            "offset" => $cp,
            "order" => "id desc"
        ]);
        $count = User::count([
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
        if ($this->request->isPost()) {

            try {
                $data_post = $this->getPost("username, email, fullname, status, role, password");

                //<editor-fold desc="Image">
                $avatar = $this->post_file_key("avatar");
                if ($avatar != null) $data_post['avatar'] = $avatar;
                //</editor-fold>

                //<editor-fold desc="Check Email">
                if ($data_post['email']) {
                    $criterial_email = [
                        'conditions' => 'email = :e:',
                        'bind' => [
                            'e' => $data_post['email']
                        ]
                    ];
                    if (!empty($id)) {
                        $criterial_email['conditions'] .= " and id != :id:";
                        $criterial_email['bind']['id'] = $id;
                    }
                    $c = User::count($criterial_email);
                    if ($c > 0) {
                        $this->flash->error('Email existed');
                        return $this->response->redirect($this->request->getHTTPReferer());
                    }
                }
                //</editor-fold>

                //<editor-fold desc="Check Username">
                if ($data_post['username']) {
                    $criterial_username = [
                        'conditions' => 'username = :u:',
                        'bind' => [
                            'u' => $data_post['username']
                        ]
                    ];
                    if (!empty($id)) {
                        $criterial_username['conditions'] .= " and id != :id:";
                        $criterial_username['bind']['id'] = $id;
                    }
                    $c = User::count($criterial_username);
                    if ($c > 0) {
                        $this->flash->error('Username existed');
                        return $this->response->redirect($this->request->getHTTPReferer());
                    }
                }
                //</editor-fold>

                if (strlen($data_post["password"])) {
                    $data_post["password"] = $this->encrypt_password($data_post['password']);
                } else {
                    unset($data_post["password"]);
                }

                // <editor-fold desc="Validate">
                if ($id > 0) {
                    $o = User::findFirst($id);
                } else {
                    $o = new User();
                }
                $o->map_object($data_post);
                // </editor-fold>


                if ($o->save()) $this->flash->success("Success");
                else $this->flash->error("Something went wrong");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        !empty($id) && $o = User::findFirst($id);
        $this->view->object = $o;
    }

    public function deleteAction()
    {
        $id = $this->request->get("id");
        $o = User::findFirst($id);
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