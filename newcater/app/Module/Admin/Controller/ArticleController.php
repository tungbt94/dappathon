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
use Model\Article;
use Model\BaseModel;
use Model\Category;
use Model\Project;

class ArticleController extends ControllerInit
{
    public function initialize()
    {
        $this->view->active_sidebar = "article/index";
        parent::initialize();
        $this->view->header = [
            'title' => 'News',
            'description' => 'News',
            'keyword' => 'News'
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

        $type = $this->request->get('type');
        if ($type >= 0 && strlen($type) > 0) {
            $query .= " and type = :type:";
            $bind['type'] = $type;
            $this->view->type = $type;
        }

        $p = $this->request->get("p");
        if ($p <= 1) $p = 1;
        $cp = ($p - 1) * $limit;
        $list_data = Article::find([
            "conditions" => $query,
            "bind" => $bind,
            "limit" => $limit,
            "offset" => $cp,
            "order" => "id desc"
        ]);
        $count = Article::count([
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
                $data_post = $this->getPost("name, description, category_id, status, seo_title, seo_description, seo_keyword");
                $data_post['content'] = $this->request->getPost('content');


                //<editor-fold desc="Image">
                $avatar = $this->post_file_key("avatar");
                if ($avatar != null) $data_post['avatar'] = $avatar;
                //</editor-fold>

                // <editor-fold desc="Validate">
                if ($id > 0) { // Update
                    $o = Article::findFirst($id);
                } else { //insert
                    $o = new Article();
                    $data_post['usercreate'] = $this->getAuth()->id;
                }
                $o->map_object($data_post);
                // </editor-fold>


                if ($o->save()) $this->flash->success("Thao tác thành công!");
                else $this->flash->error("Có lỗi xảy ra!");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        !empty($id) && $o = Article::findFirst($id);
        $this->view->object = $o;

        $list_category = Category::find(Pql::criteriaFromArray([
            'status' => BaseModel::STATUS_ACTIVE,
            'type' => 1
        ]));
        $this->view->list_category = $list_category;
    }

    public function deleteAction()
    {
        $id = $this->request->get("id");
        $o = Article::findFirst($id);
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