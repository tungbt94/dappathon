<?php


namespace Module\Admin\Controller;


use Common\Util\Helper;
use Common\Util\Pql;
use Model\BaseModel;
use Model\Category;

class CategoryController extends ControllerInit
{

    public function initialize()
    {
        $this->view->active_sidebar = "category/index";
        parent::initialize();
        $this->view->header = [
            'title' => 'Category',
            'description' => 'Category',
            'keyword' => 'Category'
        ];
    }

    public function indexAction()
    {
        $limit = 20;
        $query = "id > :s: ";
        $bind['s'] = 0;
        $q = $this->request->get("q");

        if (!empty($q)) {
            $query .= " and (name like :q: or id = :q:)";
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
        $list_data = Category::find([
            "conditions" => $query,
            "bind" => $bind,
            "limit" => $limit,
            "offset" => $cp,
            "order" => "datecreate desc"
        ]);
        $count = Category::count([
            "conditions" => $query,
            "bind" => $bind
        ]);
        $paging_info = Helper::paginginfo($count, $limit, $p);

        $this->view->setVars([
            'paging_info' => $paging_info,
            'list_data' => $list_data,
            'data_get' => $this->request->get()
        ]);
    }

    public function formAction()
    {

        $id = $this->request->get("id");
        if ($this->request->isPost()) {
            try {
                $data_post = $this->getPost("name, status");
                $avatar = $this->post_file_key("avatar");
                if ($avatar != null) $data_post['avatar'] = $avatar;

                if ($id > 0) {
                    $o = Category::findFirst($id);
                } else {
                    $o = new Category();
                    $data_post['usercreate'] = $this->getAuth()->id;
                }
                $o->map_object($data_post);

                if ($o->save()) $this->flash->success("Success");
                else $this->flash->success("Something went wrong!");
            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }
        (!empty($id)) && $o = Category::findFirst($id);
        $this->view->setVars([
            'object' => $o
        ]);
    }

    public function deleteAction()
    {
        $id = $this->request->get('id');
        if (!$id) {
            $this->flash->error('Category not found');
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $o = Category::findFirst($id);
        if ($o) {
            try {
                $o->delete();
                $this->flash->success("Xóa thành công!");
            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        } else {
            $this->flash->error("Category not found!");
        }

        $this->response->redirect($this->request->getHTTPReferer());
    }

    public function get_categoryAction()
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

        $list_data = Category::find([
            "conditions" => $query,
            "bind" => $bind,
            "order" => "id desc"
        ]);
        $count = Category::count([
            "conditions" => $query,
            "bind" => $bind
        ]);

        $list_data = $list_data->toArray();

        foreach ($list_data as &$item) {
            $html = $this->render_template('layouts', 'home_category_template', ['data' => (object)$item]);
            $item['html'] = $html;
        }

        return json_encode([
            'data' => $list_data,
            'count' => $count
        ]);
    }
}