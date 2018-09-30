<?php
/**
 * Created by PhpStorm.
 * User: Dat PC
 * Date: 4/28/2018
 * Time: 10:58 AM
 */

namespace Module\Client\Controller;

use Model\Category;
use Model\Film;
use Model\FilmCategory;
use Model\BaseModel;
use Common\Util\Helper;
use Model\Article;
use Model\Project;
use MongoDB\BSON\Binary;

class CategoryController extends ControllerBase
{
    public function indexAction()
    {
        $id = $this->dispatcher->getParam('id');
        $object = Category::findFirst($id);
        if (!$object) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }
        if ($object->status != BaseModel::STATUS_ACTIVE) {
            return $this->response->redirect($this->request->getHTTPReferer());
        }

        $limit = 9;
        $p = $this->request->get("p");
        $p = is_int(intval($p)) ? $p : 1;
        if ($p <= 1) $p = 1;
        $cp = ($p - 1) * $limit;

        $query = "id > :i: and category_id = :category_id: and status_display = :status_display:";
        $bind = ['i' => 0, 'category_id' => $id, 'status_display' => BaseModel::STATUS_ACTIVE];

        $list_data = Project::find([
            "conditions" => $query,
            "bind" => $bind,
            "limit" => $limit,
            "offset" => $cp,
            "order" => "id desc"
        ]);

        $total = Project::count([
            "conditions" => $query,
            "bind" => $bind
        ]);
        $from = $cp <= 0 ? 1 : $cp;
        $to = $from == 1 ? ($cp + $limit) : ($from + $limit);
        $to = $to > $total ? $total : $to;

        $paging_info = Helper::paginginfo($total, $limit, $p);

        $this->view->setVars(compact('list_data', 'total', 'paging_info', 'from', 'to', 'object'));

    }
}