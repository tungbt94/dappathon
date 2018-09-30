<?php

namespace Module\Client\Controller;


use Common\Util\Arrays;
use Common\Util\Helper;
use Common\Util\Pql;
use Model\BaseModel;
use Model\Category;
use Model\Project;
use Model\ProjectContribute;
use Model\User;
use Model\UserApprove;
use Model\Withdraw;
use Phalcon\Utils\ArrayUtils;

class ProjectController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        $id = $this->dispatcher->getParam('id');
        if (!$id) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }
        $object = Project::findFirst($id);
        if (!$object || $object->id != $id) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }
        if ($object->status_display != BaseModel::STATUS_ACTIVE) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($object->type != Project::TYPE_FUNDING) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $list_data = Project::find([
            'conditions' => 'id != :id: and status_display = :status_display: and category_id = :category_id:',
            'bind' => [
                'category_id' => $object->category_id,
                'id' => $id,
                'status_display' => BaseModel::STATUS_ACTIVE
            ]
        ]);

        $withdraw = Withdraw::findFirst(Pql::criteriaFromArray([
            'project_id' => $object->id,
            'status' => BaseModel::STATUS_APPROVE
        ]));

        if ($this->getAuth()) {
            $contribute = ProjectContribute::findFirst(Pql::criteriaFromArray([
                'user_id' => $this->getAuth()->id,
                'project_id' => $object->id
            ]));

            $user_approve = UserApprove::findFirst(Pql::criteriaFromArray([
                'withdraw_id' => $withdraw->id,
                'project_id' => $object->id,
                'user_id' => $this->getAuth()->id
            ]));
        }

        $list_contribute = ProjectContribute::sum([
            'conditions' => 'project_id = :project_id:',
            'bind' => [
                'project_id' => $id
            ],
            'group' => 'contribute_address',
            'column' => 'value'
        ]);

        $this->view->setVars(compact('object', 'list_data', 'withdraw', 'contribute', 'user_approve', 'list_contribute'));
    }

    public function addAction()
    {
        $list_category = Category::find(Pql::criteriaFromArray([
            'status' => BaseModel::STATUS_ACTIVE
        ]));
        if ($this->request->isPost()) {

            //<editor-fold desc="Check Login">
            $pioneer_info = $this->getAuth();
            if (!$pioneer_info) {
                return $this->flash->error('Please login to publish your project');
            }
            //</editor-fold>

            $list_field = Project::getFieldProperties([
                'datecreate', 'usercreate', 'status', 'status_display', 'contribute_address', 'raised', 'funding_receipt'
            ]);

            $list_field = (implode(",", $list_field));
            $data_post = $this->getPost($list_field);
            $avatar = $this->post_file_key("avatar");
            if ($avatar != null) $data_post['avatar'] = $avatar;
            $data_post['content'] = $this->request->getPost('content');


            $this->session->set("data_post", $data_post);

            //<editor-fold desc="Recaptcha">
            $recaptcha = $_POST['g-recaptcha-response'];
            $recaptcha = $this->verify_captcha($recaptcha);
            if (empty($recaptcha->success) || $recaptcha->success != 1) {
                $this->flash->error('You must verify captcha to do this action');
                return $this->response->redirect($this->request->getHTTPReferer());
            }
            //</editor-fold>

            //<editor-fold desc="Validate">
            if (strlen($data_post['name']) <= 0) {
                $this->flash->error("Please enter a Project Name!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z0-9-]{2,30})$/';

            if (!preg_match($regex, $data_post['contact_email'])) {
                $this->flash->error("Please enter a valid Contact Email!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['contact_person']) <= 0) {
                $this->flash->error("Please enter Contact Person!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['avatar']) <= 0) {
                $this->flash->error("Please upload avatar project!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['description']) <= 0) {
                $this->flash->error("Please enter description!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['content']) <= 0) {
                $this->flash->error("Please enter content!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }
            //</editor-fold>

            if (!$data_post['category_id']) unset($data_post['category_id']);

            $data_post['status_display'] = BaseModel::STATUS_INACTIVE;
            $data_post['status'] = Project::STATUS_PENDING;
            $data_post['type'] = Project::TYPE_FUNDING;

            $data_post['contribute_start_time'] = Helper::process_date_form($data_post['contribute_start_time']);
            $data_post['contribute_end_time'] = Helper::process_date_form($data_post['contribute_end_time']);

            //<editor-fold desc="Save Project">
            $data_post += [
                'usercreate' => $this->getAuth()->id,
                'funding_receipt' => $this->getAuth()->eth_address
            ];

            $o = new Project();
            $o->map_object($data_post);
            if ($o->save()) {
                $this->session->remove("data_post");
                $this->flash->success('Success');

                unset($data_post);
                $this->session->remove('data_post');
            };
            //</editor-fold>

        }

        $data = $this->session->get("data_post");
        if (!$data && count($data) <= 0) {
            if ($o) $data_post = $o->toArray();
            else $data_post = [];

        } else {
            $data_post = $data;
        }

        $this->view->setVars(compact('list_category', 'data_post'));
    }

    public function add_auctionAction()
    {
        if ($this->request->isPost()) {

            //<editor-fold desc="Check Login">
            $pioneer_info = $this->getAuth();
            if (!$pioneer_info) {
                return $this->flash->error('Please login to publish your project');
            }
            //</editor-fold>

            $list_field = Project::getFieldProperties([
                'datecreate', 'usercreate', 'status', 'status_display', 'contribute_address', 'target', 'category_id', 'raised', 'percent_approve',
                'contribute_address', 'funding_receipt', 'percent_terminate', 'percent_consensus',
                'winner_user_id', 'highest_value', 'highest_address'
            ]);

            $list_field = (implode(",", $list_field));
            $data_post = $this->getPost($list_field);
            $avatar = $this->post_file_key("avatar");
            if ($avatar != null) $data_post['avatar'] = $avatar;
            $data_post['content'] = $this->request->getPost('content');


            $this->session->set("data_post", $data_post);

            //<editor-fold desc="Recaptcha">
            $recaptcha = $_POST['g-recaptcha-response'];
            $recaptcha = $this->verify_captcha($recaptcha);
            if (empty($recaptcha->success) || $recaptcha->success != 1) {
                $this->flash->error('You must verify captcha to do this action');
                return $this->response->redirect($this->request->getHTTPReferer());
            }
            //</editor-fold>

            //<editor-fold desc="Validate">
            if (strlen($data_post['name']) <= 0) {
                $this->flash->error("Please enter a name!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z0-9-]{2,30})$/';

            if (!preg_match($regex, $data_post['contact_email'])) {
                $this->flash->error("Please enter a valid Contact Email!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['contact_person']) <= 0) {
                $this->flash->error("Please enter Contact Person!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['avatar']) <= 0) {
                $this->flash->error("Please upload avatar project!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['description']) <= 0) {
                $this->flash->error("Please enter description!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['content']) <= 0) {
                $this->flash->error("Please enter content!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }
            //</editor-fold>

            $data_post['status_display'] = BaseModel::STATUS_INACTIVE;
            $data_post['status'] = Project::STATUS_PENDING;
            $data_post['type'] = Project::TYPE_AUCTION;

            $data_post['contribute_start_time'] = Helper::process_date_form($data_post['contribute_start_time']);
            $data_post['contribute_end_time'] = Helper::process_date_form($data_post['contribute_end_time']);

            //<editor-fold desc="Save Project">
            $data_post += [
                'usercreate' => $this->getAuth()->id,
                'funding_receipt' => $this->getAuth()->eth_address
            ];

            $o = new Project();
            $o->map_object($data_post);
            if ($o->save()) {
                $this->session->remove("data_post");
                $this->flash->success('Success');

                unset($data_post);
                $this->session->remove('data_post');
            };
            //</editor-fold>

        }

        $data = $this->session->get("data_post");
        if (!$data && count($data) <= 0) {
            if ($o) $data_post = $o->toArray();
            else $data_post = [];

        } else {
            $data_post = $data;
        }

        $this->view->setVars(compact('data_post'));
    }

    public function withdrawAction()
    {
        if (!$this->getAuth()) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $id = $this->request->getQuery('id');
        if (!$id) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $object = Project::findFirst($id);
        if (!$object || $object->id != $id) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($object->usercreate != $this->getAuth()->id) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($this->request->isPost()) {

            $check = Withdraw::count([
                'conditions' => 'project_id = :project_id: and status in ({status:array})',
                'bind' => [
                    'project_id' => $object->id,
                    'status' => [
                        BaseModel::STATUS_PENDING,
                        BaseModel::STATUS_APPROVE
                    ]
                ]
            ]);

            if ($check) {
                return $this->flash->error("Project have withdrawal not finished");
            }

            $data_post = $this->getPost();

            if (!$data_post['vote_start_time']) {
                return $this->flash->error('Please pick the voting start time');
            }

            if (!$data_post['vote_end_time']) {
                return $this->flash->error('Please pick the voting end time');
            }

            if (!$data_post['amount'] || $data_post['amount'] <= 0) {
                return $this->flash->error('Invalid Amount');
            }

            $data_post['vote_start_time'] = Helper::process_date_form($data_post['vote_start_time']);
            $data_post['vote_end_time'] = Helper::process_date_form($data_post['vote_end_time']);

            $data_post['usercreate'] = $this->getAuth()->id;
            $data_post['project_id'] = $id;
            $o = new Withdraw();
            $o->map_object($data_post);
            $o->save();
            $this->flash->success('Success');
        }

        $list_data = Withdraw::find([
            'conditions' => 'project_id = :project_id:',
            'bind' => [
                'project_id' => $id
            ],
            'limit' => 10,
            'order' => 'id desc'
        ]);

        $this->view->setVars([
            'object' => $object,
            'list_data' => $list_data
        ]);
    }

    public function history_withdrawAction()
    {

    }

    public function bidAction()
    {
        $id = $this->dispatcher->getParam('id');


        if (!$id) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }
        $object = Project::findFirst($id);
        if (!$object || $object->id != $id) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }
        if ($object->status_display != BaseModel::STATUS_ACTIVE) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($object->type != Project::TYPE_AUCTION) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $list_data = Project::find([
            'conditions' => 'id != :id: and status_display = :status_display: and type = :type: and contribute_end_time > :contribute_end_time:',
            'bind' => [
                'contribute_end_time' => time(),
                'type' => Project::TYPE_AUCTION,
                'id' => $id,
                'status_display' => BaseModel::STATUS_ACTIVE
            ]
        ]);

        $list_contribute = ProjectContribute::sum([
            'conditions' => 'project_id = :project_id:',
            'bind' => [
                'project_id' => $id
            ],
            'group' => 'contribute_address',
            'column' => 'value'
        ]);

        $this->view->setVars(compact('object', 'list_data', 'withdraw', 'contribute', 'user_approve', 'list_contribute'));
    }

    public function add_tokenAction()
    {
        if ($this->request->isPost()) {

            //<editor-fold desc="Check Login">
            $pioneer_info = $this->getAuth();
            if (!$pioneer_info) {
                return $this->flash->error('Please login to publish your project');
            }
            //</editor-fold>

            $list_field = Project::getFieldProperties([
                'datecreate', 'usercreate', 'status', 'status_display', 'contribute_address', 'target', 'category_id', 'raised', 'percent_approve',
                'contribute_address', 'funding_receipt', 'percent_terminate', 'percent_consensus',
                'winner_user_id', 'highest_value', 'highest_address'
            ]);

            $list_field = (implode(",", $list_field));
            $data_post = $this->getPost($list_field);
            $avatar = $this->post_file_key("avatar");
            if ($avatar != null) $data_post['avatar'] = $avatar;
            $data_post['content'] = $this->request->getPost('content');


            $this->session->set("data_post", $data_post);

            //<editor-fold desc="Recaptcha">
            $recaptcha = $_POST['g-recaptcha-response'];
            $recaptcha = $this->verify_captcha($recaptcha);
            if (empty($recaptcha->success) || $recaptcha->success != 1) {
                $this->flash->error('You must verify captcha to do this action');
                return $this->response->redirect($this->request->getHTTPReferer());
            }
            //</editor-fold>

            //<editor-fold desc="Validate">
            if (strlen($data_post['name']) <= 0) {
                $this->flash->error("Please enter a name!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z0-9-]{2,30})$/';

            if (!preg_match($regex, $data_post['contact_email'])) {
                $this->flash->error("Please enter a valid Contact Email!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['contact_person']) <= 0) {
                $this->flash->error("Please enter Contact Person!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['avatar']) <= 0) {
                $this->flash->error("Please upload avatar project!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['description']) <= 0) {
                $this->flash->error("Please enter description!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if (strlen($data_post['content']) <= 0) {
                $this->flash->error("Please enter content!");
                return $this->response->redirect($this->request->getHTTPReferer());
            }
            //</editor-fold>

            $data_post['status_display'] = BaseModel::STATUS_INACTIVE;
            $data_post['status'] = Project::STATUS_PENDING;
            $data_post['type'] = Project::TYPE_AUCTION;

            $data_post['contribute_start_time'] = Helper::process_date_form($data_post['contribute_start_time']);
            $data_post['contribute_end_time'] = Helper::process_date_form($data_post['contribute_end_time']);

            //<editor-fold desc="Save Project">
            $data_post += [
                'usercreate' => $this->getAuth()->id,
                'funding_receipt' => $this->getAuth()->eth_address
            ];

            $o = new Project();
            $o->map_object($data_post);
            if ($o->save()) {
                $this->session->remove("data_post");
                $this->flash->success('Success');

                unset($data_post);
                $this->session->remove('data_post');
            };
            //</editor-fold>

        }

        $data = $this->session->get("data_post");
        if (!$data && count($data) <= 0) {
            if ($o) $data_post = $o->toArray();
            else $data_post = [];

        } else {
            $data_post = $data;
        }

        $this->view->setVars(compact('data_post'));
    }

    public function uploadAction()
    {
        global $config;
        if (isset($_POST) && !empty($_FILES['avatar'])) {
            $avatar = $this->post_file_key('avatar');
            $url = $config->media->host . $avatar;
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, ['url' => $url], 'Success');
        } else {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'File not found');
        }
    }

    public function collectAction()
    {
        $id = $this->dispatcher->getParam('id');


        if (!$id) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }
        $object = Project::findFirst($id);
        if (!$object || $object->id != $id) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }
        if ($object->status_display != BaseModel::STATUS_ACTIVE) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $list_data = Project::find([
            'conditions' => 'id != :id: and status_display = :status_display: and type = :type: and contribute_end_time > :contribute_end_time:',
            'bind' => [
                'contribute_end_time' => time(),
                'type' => Project::TYPE_AUCTION,
                'id' => $id,
                'status_display' => BaseModel::STATUS_ACTIVE
            ]
        ]);

        $list_contribute = ProjectContribute::sum([
            'conditions' => 'project_id = :project_id:',
            'bind' => [
                'project_id' => $id
            ],
            'group' => 'contribute_address',
            'column' => 'value'
        ]);

        $this->view->setVars(compact('object', 'list_data', 'withdraw', 'contribute', 'user_approve', 'list_contribute'));
    }
}