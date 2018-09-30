<?php
/**
 * Created by PhpStorm.
 * User: lemin
 * Date: 7/2/2018
 * Time: 6:16 AM
 */

namespace Module\Admin\Controller;


use Common\Util\Helper;
use Common\Util\Pql;
use Model\BaseModel;
use Model\Category;
use Model\Project;

class ProjectController extends ControllerInit
{
    public function initialize()
    {
        $this->view->active_sidebar = "project/index";
        parent::initialize();
        $this->view->header = [
            'title' => 'Project',
            'description' => 'Project',
            'keyword' => 'Project'
        ];
    }

    public function indexAction()
    {
        $limit = 20;
        $query = "id > :s: and type = :type:";
        $bind['s'] = 0;
        $bind['type'] = Project::TYPE_FUNDING;
        $q = $this->request->get("q");
        if (!empty($q)) {
            $query .= " and (name like :q: or id = :q:)";
            $bind += ['q' => "%$q%"];
        }

        $status_display = $this->request->get('status_display');
        if ($status_display >= 0 && strlen($status_display) > 0) {
            $query .= " and status_display = :s:";
            $bind['s'] = $status_display;
            $this->view->status_display = $status_display;
        }

        $p = $this->request->get("p");
        if ($p <= 1) $p = 1;
        $cp = ($p - 1) * $limit;
        $list_data = Project::find([
            "conditions" => $query,
            "bind" => $bind,
            "limit" => $limit,
            "offset" => $cp,
            "order" => "id desc"
        ]);
        $count = Project::count([
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
                $data_post = $this->getPost("name, contact_email, contact_person, target, video, description, category_id");
                $data_post['content'] = $this->request->getPost('content');

                //<editor-fold desc="Avatar">
                $avatar = $this->post_file_key("avatar");
                if ($avatar != null) $data_post['avatar'] = $avatar;
                //</editor-fold>

                // <editor-fold desc="Validate">
                if ($id > 0) {
                    $o = Project::findFirst($id);
                    if ($o->type != Project::TYPE_FUNDING) {
                        $this->flash->warning('Not Funding Project');
                        return $this->response->redirect($this->request->getHTTPReferer());
                    }
                } else {
                    $o = new Project();
                    $data_post['usercreate'] = $this->getAuth()->id;
                    $data_post['type'] = Project::TYPE_FUNDING;
                }
                $o->map_object($data_post);
                // </editor-fold>


                if ($o->save()) $this->flash->success("Success");
                else $this->flash->error("Something went wrong");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        !empty($id) && $o = Project::findFirst($id);
        $list_category = Category::find(Pql::criteriaFromArray([
            'status' => BaseModel::STATUS_ACTIVE
        ]));
        $this->view->setVars([
            'object' => $o,
            'list_category' => $list_category
        ]);
    }

    public function updateAction()
    {
        $id = $this->request->get("id");

        if (!$id) {
            $this->flash->error('Project not found');
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($this->request->isPost()) {

            try {
                $data_post = $this->getPost('status_display');

                // <editor-fold desc="Validate">
                if ($id > 0) {
                    $o = Project::findFirst($id);
                    if ($o->type != Project::TYPE_FUNDING) {
                        $this->flash->warning('Not funding Project');
                        return $this->response->redirect($this->request->getHTTPReferer());
                    }
                } else {
                    $o = new Project();
                    $data_post['type'] = Project::TYPE_FUNDING;
                }
                $o->map_object($data_post);
                // </editor-fold>

                if ($o->save()) $this->flash->success("Success");
                else $this->flash->error("Something went wrong");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        !empty($id) && $o = Project::findFirst($id);
        $this->view->setVars([
            'object' => $o
        ]);
    }

    public function auctionAction()
    {
        $limit = 20;
        $query = "id > :s: and type = :type:";
        $bind['s'] = 0;
        $bind['type'] = Project::TYPE_AUCTION;
        $q = $this->request->get("q");
        if (!empty($q)) {
            $query .= " and (name like :q: or id = :q:)";
            $bind += ['q' => "%$q%"];
        }

        $status_display = $this->request->get('status_display');
        if ($status_display >= 0 && strlen($status_display) > 0) {
            $query .= " and status_display = :s:";
            $bind['s'] = $status_display;
            $this->view->status_display = $status_display;
        }

        $p = $this->request->get("p");
        if ($p <= 1) $p = 1;
        $cp = ($p - 1) * $limit;
        $list_data = Project::find([
            "conditions" => $query,
            "bind" => $bind,
            "limit" => $limit,
            "offset" => $cp,
            "order" => "id desc"
        ]);
        $count = Project::count([
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

    public function auction_formAction()
    {
        $id = $this->request->get("id");
        if ($this->request->isPost()) {

            try {
                $data_post = $this->getPost("name, contact_email, contact_person, video, description, min_value, step_value, contribute_start_time, contribute_end_time");
                $data_post['content'] = $this->request->getPost('content');

                //<editor-fold desc="Avatar">
                $avatar = $this->post_file_key("avatar");
                if ($avatar != null) $data_post['avatar'] = $avatar;
                //</editor-fold>

                // <editor-fold desc="Validate">
                if ($id > 0) {
                    $o = Project::findFirst($id);
                } else {
                    $o = new Project();
                    $data_post['usercreate'] = $this->getAuth()->id;
                    $data_post['type'] = Project::TYPE_AUCTION;
                }

                $data_post['contribute_start_time'] = Helper::process_date_form($data_post['contribute_start_time']);
                $data_post['contribute_end_time'] = Helper::process_date_form($data_post['contribute_end_time']);

                $o->map_object($data_post);
                // </editor-fold>


                if ($o->save()) $this->flash->success("Success");
                else $this->flash->error("Something went wrong");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        !empty($id) && $o = Project::findFirst($id);
        $this->view->setVars([
            'object' => $o
        ]);
    }

    public function update_auctionAction(){
        $id = $this->request->get("id");

        if (!$id) {
            $this->flash->error('Project not found');
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        if ($this->request->isPost()) {

            try {
                $data_post = $this->getPost('status_display');

                // <editor-fold desc="Validate">
                if ($id > 0) {
                    $o = Project::findFirst($id);
                    if ($o->type != Project::TYPE_AUCTION) {
                        $this->flash->warning('Not Auction Project');
                        return $this->response->redirect($this->request->getHTTPReferer());
                    }
                } else {
                    $o = new Project();
                    $data_post['type'] = Project::TYPE_FUNDING;
                }
                $o->map_object($data_post);
                // </editor-fold>

                if ($o->save()) $this->flash->success("Success");
                else $this->flash->error("Something went wrong");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        !empty($id) && $o = Project::findFirst($id);
        $this->view->setVars([
            'object' => $o
        ]);
    }

    public function deleteAction()
    {
        $id = $this->request->get("id");
        $o = Project::findFirst($id);
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

    public function get_productAction()
    {
        $this->view->disable();
        header("Content-Type:application/json;charset=utf-8");
        $query = "id > :i: ";
        $bind = ['i' => 0];

        $q = $this->request->getQuery('q', 'string');
        if (strlen($q)) {
            $query .= " and (name like :q:)";
            $bind += ['q' => "%$q%"];
        }

        $list_data = Project::find([
            "conditions" => $query,
            "bind" => $bind,
            "order" => "id desc"
        ]);
        $count = Project::count([
            "conditions" => $query,
            "bind" => $bind
        ]);

        $list_data = $list_data->toArray();

        foreach ($list_data as &$item) {
            $html = $this->render_template('layouts', 'order_product_template', ['data' => (object)$item]);
            $item['html'] = $html;
        }

        return json_encode([
            'data' => $list_data,
            'count' => $count
        ]);
    }
}