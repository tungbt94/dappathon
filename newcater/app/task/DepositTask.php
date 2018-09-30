<?php
/**
 * Created by PhpStorm.
 * User: Vivi
 * Date: 5/20/2018
 * Time: 5:08 PM
 */
require __DIR__ . '/../../vendor/autoload.php';

use Common\Util\Helper;
use Common\Util\Model;
use Common\Util\Pql;
use Model\BaseModel;
use Model\Project;
use Model\ProjectContribute;
use Model\User;
use Phalcon\Cli\Task;

use Httpful\Http;
use Httpful\Mime;
use Httpful\Request;

class DepositTask extends Task
{
    /**
     */
    public function ethAction()
    {
        try {
            $config = $this->di->get('config');
            $url = $config->api->url;
            $list_project = Project::find([
                'conditions' => 'status_display = :status_display: and contribute_address is not null',
                'bind' => [
                    'status_display' => BaseModel::STATUS_ACTIVE
                ]
            ]);

            $api_key = $config->api->api_key;
            foreach ($list_project as $project_item) {
                $address  = $project_item->contribute_address;
                $api_url = "$url/api?module=account&action=txlist&address=$address&startblock=0&endblock=999999999&page=1&offset=100&sort=desc&apikey=$api_key";
                $response = Request::get($api_url)->autoParse(true)->expectsType("application/json")->send();
                $result = $response->body->result;
                $i = 0;
                if (is_array($result) && count($result)) {
                    $this->db->begin();
                    foreach ($result as $item) {

                        //<editor-fold desc="Check To Address">
                        $address = strtolower($address);
                        $to = strtolower($item->to);
                        if ($to != $address) continue;
                        //</editor-fold>

                        if ($item->isError == "1") continue;

                        //<editor-fold desc="Cal Real Value">
                        $value = $item->value;
                        if ($value <= 0) continue;
                        $real_value = $value * pow(10, -18);
                        $item->real_value = $real_value;
                        //</editor-fold>



                        //<editor-fold desc="Check Project Contribute">
                        $project_c = ProjectContribute::count([
                            'conditions' => 'hash = :hash:',
                            'bind' => [
                                'hash' => $item->hash
                            ]
                        ]);
                        if ($project_c) continue;
                        //</editor-fold>

                        //<editor-fold desc="Check user">
                        $user = User::findFirst(Pql::criteriaFromArray([
                            'eth_address' => $item->from
                        ]));
                        if (!$user || strtolower($user->eth_address) != strtolower($item->from)) {
                            $user = new User();
                            $user->eth_address = $address;
                            $user->status = BaseModel::STATUS_ACTIVE;
                            $user->role = 1;
                            $user->save();
                        };
                        //</editor-fold>

                        $data_post = [
                            'project_id' => $project_item->id,
                            'user_id' => $user->id,
                            'project_address' => $project_item->contribute_address,
                            'contribute_address' => $item->from,
                            'datecreate' => time(),
                            'hash' => $item->hash,
                            'value' => $real_value
                        ];
                        $project_contribute = new ProjectContribute();
                        $project_contribute->map_object($data_post);
                        $project_contribute->save();

                        $i++;
                    }
                    $this->db->commit();
                }
                if ($i <= 0) echo date("d/m/Y H:i") . ": None" . PHP_EOL;
            }

        } catch (Exception $e) {
            echo date("d/m/Y H:i") . ": " . $e->getMessage() . PHP_EOL;
            die;
        }

    }
}