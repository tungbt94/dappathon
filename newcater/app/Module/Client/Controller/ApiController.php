<?php
/**
 * Created by PhpStorm.
 * User: lemin
 * Date: 7/20/2018
 * Time: 1:59 PM
 */

namespace Module\Client\Controller;
include __DIR__ . '/../../../../vendor/autoload.php';

use Common\Util\Helper;
use Common\Util\Pql;
use Httpful\Request;
use HttpRequest;
use Model\BaseModel;
use Model\Project;
use Model\ProjectContribute;
use Model\User;
use Model\UserApprove;
use Model\Withdraw;

class ApiController extends ControllerBase
{
    public function initialize()
    {
        $this->view->disable();
    }

    public function create_userAction()
    {
        $address = $this->request->getPost('address');
        if (!$address) {
            echo $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Invalid Address');
            die;
        }
        $user = User::findFirst([
            'conditions' => 'eth_address = :eth_address:',
            'bind' => [
                'eth_address' => $address
            ]
        ]);
        if (!$user || $user->eth_address != $address) {
            $user = new User();
            $user->eth_address = $address;
            $user->status = BaseModel::STATUS_ACTIVE;
            $user->role = 1;
            $user->save();
        }
        $data_user = $user->toArray();
        $data_user['short_address'] = $user->getAddress();
        $this->setAuth($user);
        echo $this->setDataJson(BaseModel::STATUS_ACTIVE, $data_user, 'Successs');
        die;
    }

    public function update_address_contractAction()
    {
        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Invalid Project ID");
        }

        $project = Project::findFirst($id);
        if (!$project || $project->id != $id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Project Not Found");
        }

        $address = $this->request->getPost('address');

        if (!$address) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Invalid Address');
        }
        $hash = $this->request->getPost('hash');

        $project->contribute_address = $address;
        $project->hash = $hash;
        $project->status_display = BaseModel::STATUS_ACTIVE;
        $project->status = Project::STATUS_RUNNING;
        $project->save();
        return $this->setDataJson(BaseModel::STATUS_ACTIVE, $project, 'Success');

    }

    /**
     * @return string
     */
    public function create_project_contributeAction()
    {
        $user_info = $this->getAuth();
        if (!$user_info) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Not Authorize');
        }

        $data_post = $this->request->getPost();
        $project_id = $data_post['project_id'];
        if (!$project_id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Project Not Found');
        }

        $project = Project::findFirst($project_id);
        if (!$project || $project->id != $project_id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Project Not Found');
        }

        $data_post['user_id'] = $user_info->id;
        $data_post['project_address'] = $project->contribute_address;
        $data_post['contribute_address'] = $user_info->eth_address;
        $project_contribute = new ProjectContribute();
        $project_contribute->map_object($data_post);
        $project_contribute->save();

        /*$url = "https://api-ropsten.etherscan.io";
        $api_url = "$url/api?module=account&action=balance&address={$project->contribute_address}&tag=latest&apikey=YourApiKeyToken";
        $response = Request::get($api_url)->autoParse(true)->expectsType("application/json")->send();
        $balance = $response->body->result;
        $balance = $balance * pow(10, -18);*/

        $raised = $data_post['raised'];
        $balance = $data_post['balance'];
        $project->raised = $raised;
        $project->balance = $balance;
        $project->save();

        return $this->setDataJson(BaseModel::STATUS_ACTIVE, $project_contribute, "Success");
    }

    public function update_withdrawAction()
    {
        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Invalid Withdraw ID");
        }

        $withdraw = Withdraw::findFirst($id);
        if (!$withdraw || $withdraw->id != $id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Withdraw Not Found");
        }

        $hash = $this->request->getPost('hash');

        if (!$hash) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Invalid Transaction ID');
        }

        $withdraw->hash = $hash;
        $withdraw->status = BaseModel::STATUS_APPROVE;
        $withdraw->save();
        return $this->setDataJson(BaseModel::STATUS_ACTIVE, $withdraw, 'Success');
    }

    public function accept_withdrawAction()
    {
        $user_info = $this->getAuth();
        if (!$user_info) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Not Authorize');
        }

        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Invalid Project ID");
        }

        $project = Project::findFirst($id);
        if (!$project || $project->id != $id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Project Not Found");
        }

        $withdraw = Withdraw::findFirst(Pql::criteriaFromArray([
            'project_id' => $project->id,
            'status' => BaseModel::STATUS_APPROVE
        ]));

        if (!$withdraw) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Withdraw Not Found");
        }

        $hash = $this->request->getPost('hash');

        $user_approve = new UserApprove();
        $user_approve->project_id = $id;
        $user_approve->user_id = $this->getAuth()->id;
        $user_approve->withdraw_id = $withdraw->id;
        $user_approve->hash = $hash;
        $user_approve->save();
        return $this->setDataJson(BaseModel::STATUS_ACTIVE, $user_approve, "Success");


    }

    public function un_accept_withdrawAction()
    {
        $user_info = $this->getAuth();
        if (!$user_info) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Not Authorize');
        }

        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Invalid Project ID");
        }

        $project = Project::findFirst($id);
        if (!$project || $project->id != $id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Project Not Found");
        }

        $withdraw = Withdraw::findFirst(Pql::criteriaFromArray([
            'project_id' => $project->id,
            'status' => BaseModel::STATUS_APPROVE
        ]));

        if (!$withdraw) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Withdraw Not Found");
        }


        $user_approve = UserApprove::findFirst(Pql::criteriaFromArray([
            'user_id' => $this->getAuth()->id,
            'project_id' => $id,
            'withdraw_id' => $withdraw->id,
        ]));
        if ($user_approve) $user_approve->delete();
        return $this->setDataJson(BaseModel::STATUS_ACTIVE, null, "Success");
    }

    public function update_project_infoAction()
    {
        $id = $this->request->getPost('id');
        $raised = $this->request->getPost('raised');
        $balance = $this->request->getPost('balance');
        $current_warrior = $this->request->getPost('current_warrior');
        $highest_value = $this->request->getPost('highest_value');

        if (!$id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Project ID Not Found');
        }
        $project = Project::findFirst($id);
        if (!$project) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Project Not Found');
        }

        if ($this->request->hasPost('raised')) $project->raised = $raised;
        if ($this->request->hasPost('balance')) $project->balance = $balance;
        if ($this->request->hasPost('current_warrior')) $project->current_warrior = $current_warrior;
        if ($this->request->hasPost('highest_value')) $project->highest_value = $highest_value;

        $project->save();
        return $this->setDataJson(BaseModel::STATUS_ACTIVE, $project, 'Success');
    }

    public function create_withdrawAction()
    {
        $user_info = $this->getAuth();
        if (!$user_info) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, 'Not Authorize');
        }

        $project_id = $this->request->getPost('project_id');
        if (!$project_id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Invalid Project ID");
        }

        $project = Project::findFirst($project_id);
        if (!$project || $project->id != $project_id) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Project Not Found");
        }

        $check = Withdraw::count([
            'conditions' => 'project_id = :project_id: and status in ({status:array})',
            'bind' => [
                'project_id' => $project_id,
                'status' => [
                    BaseModel::STATUS_PENDING,
                    BaseModel::STATUS_APPROVE
                ]
            ]
        ]);

        if ($check) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Project have withdrawal not finished");
        }

        $data_post = $this->getPost();

        if (!$data_post['vote_start_time']) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Please pick the voting start time");
        }

        if (!$data_post['vote_end_time']) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Please pick the voting end time");
        }

        if (!$data_post['amount'] || $data_post['amount'] <= 0) {
            return $this->setDataJson(BaseModel::STATUS_INACTIVE, null, "Invalid Amount");
        }

        $data_post['usercreate'] = $this->getAuth()->id;
        $data_post['project_id'] = $project_id;
        $o = new Withdraw();
        $o->map_object($data_post);
        $o->save();
        return $this->setDataJson(BaseModel::STATUS_ACTIVE, $o, 'Success');
    }

    public function create_projectAction()
    {
        if ($this->request->isPost()) {

            $list_field = Project::getFieldProperties([
                'datecreate', 'usercreate', 'status', 'status_display', 'contribute_address', 'target', 'category_id', 'raised', 'percent_approve',
                'contribute_address', 'funding_receipt', 'percent_terminate', 'percent_consensus',
                'winner_user_id', 'highest_value', 'highest_address'
            ]);

            $list_field = (implode(",", $list_field));
            $data_post = $this->getPost($list_field);
            /*$avatar = $this->post_file_key("avatar");
            if ($avatar != null) $data_post['avatar'] = $avatar;*/
            $data_post['avatar'] = 'client_resource/assets/img/wukong.png';
            $data_post['contribute_address'] = '0xc7e08CE0764369bd4f58Fddb4457cD90398D93c5';
            $data_post['content'] = $this->request->getPost('content');

            $this->session->set("data_post", $data_post);

            $data_post['status_display'] = BaseModel::STATUS_ACTIVE;
            $data_post['status'] = Project::STATUS_RUNNING;
            $data_post['type'] = Project::TYPE_COLLECT;

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
        return $this->setDataJson(BaseModel::STATUS_ACTIVE, $o, "Success");

    }
}