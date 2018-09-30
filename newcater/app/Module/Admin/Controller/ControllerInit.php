<?php
/**
 * Created by PhpStorm.
 * User: Dat PC
 * Date: 4/25/2018
 * Time: 2:34 PM
 */

namespace Module\Admin\Controller;

use Common\Auth\Auth;
use Common\Auth\Manager;
use Common\Util\Helper;
use Phalcon\Mvc\Controller;
use Common\Util\Module as Module;
use Model\User;
use Model\BaseModel;


/**
 * Class ControllerInit
 *
 * @package Module\Admin\Controller
 *
 * @property Manager $authManager
 */
class ControllerInit extends ControllerBase
{
    public function initialize()
    {


        //<editor-fold desc="Get Full Controller">
        $module_name = $this->dispatcher->getModuleName();
        $controller_name = $this->dispatcher->getControllerName();
        $action_name = $this->dispatcher->getActionName();
        $full_action = "{$module_name}/{$controller_name}/$action_name";
        //</editor-fold>

        //<editor-fold desc="Check login">
        if (!$this->getAuth() && $full_action != 'admin/auth/login') {
//            header('Location: /admin/auth/login?redirect=' . actual_request());
//            die;
        }

        if ($this->getUserInfo() && $this->getUserInfo()->role != 1 && $full_action != 'admin/auth/login') {
//            return $this->response->redirect('/auth/login');
        }

        //</editor-fold>

        $side_bar = Module::getMenu();

        $this->user_info = $this->getAuth();
        $this->view->user_info = $this->user_info;

        $status_inactive = BaseModel::STATUS_INACTIVE;
        $status_active = BaseModel::STATUS_ACTIVE;
        $status_pending = BaseModel::STATUS_PENDING;
        global $config;
        $back_url = $this->request->getHTTPReferer();
        $this->view->setVars(compact('user_info', 'side_bar', 'status_active', 'status_inactive', 'status_pending', 'status_approve', 'status_reject', 'config', 'back_url'));
    }


    public function showDebug()
    {
        ini_set("display_errors", 1);
        error_reporting(E_ALL);
    }

    public function render_template($controller, $action, $data = null)
    {
        $view = $this->view;
        $content = $view->getRender($controller, $action, ["object" => $data], function ($view) {
            $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
        });

        return $content;
    }

    public function post_file_key($key, $fullpath = false)
    {
        if (!isset($_FILES["$key"])) return null;
        $target_dir = $this->config->media->dir;
        $folder = "uploads/" . date("Y/m/d");
        $list_allow = [
            "jpg",
            "gif",
            "jpeg",
            "png",
            "txt"
        ];
        $fileParts = strtolower(pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION));
        $folder_name = '/general/';
        if (in_array($fileParts, [
            'jpg',
            'jpeg',
            'gif',
            'png'
        ])) {
            $folder_name = '/picture/';
        }
        if (in_array($fileParts, [
            'mp3',
            'mp4',
            'avi',
            'mkv'
        ])) {
            $folder_name = '/video/';
        }
        if (in_array($fileParts, ['srt'])) {
            $folder_name = '/sub/';
        }
        if (!file_exists($target_dir . $folder . $folder_name)) mkdir($target_dir . $folder . $folder_name, 0777, true);
        $target_file = $folder . $folder_name . basename(md5(strtotime("now") . uniqid() . rand(0, 9999)) . "_" . Helper::removeTitle($_FILES["$key"]["name"]));

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (file_exists($target_file)) return null;
        if ($_FILES["$key"]["size"] <= 0) return null;
        if (!in_array($imageFileType, $list_allow)) return null;
        move_uploaded_file($_FILES["$key"]["tmp_name"], $target_dir . $target_file);
        if ($fullpath == true) return $target_dir . $target_file;
        else return $target_file;
    }

    public function post_file_to_array($field = null, $return_array = true)
    {
        $list_image = [];
        $avatar = null;
        if ($this->request->hasFiles()) {
            $target_dir = $this->config->media->dir;
            $folder = "uploads/" . date("Y/m/d");
            $list_allow = [
                "jpg",
                "jpeg",
                "png",
                "gif",
                "mp3",
                "mp4",
                "xlsx",
                "xls"
            ];
            $uploads = $this->request->getUploadedFiles();
            if (!empty($field) && is_string($field)) $field = explode(",", $field);
            foreach ($uploads as $upload) {

                $fileParts = strtolower($upload->getExtension());

                if (!$fileParts) continue;
                if (in_array($fileParts, [
                    'jpg',
                    'jpeg',
                    'gif',
                    'png'
                ])) {
                    $folder_name = '/picture/';
                }
                if (in_array($fileParts, [
                    'mp3',
                    'mp4',
                    'avi',
                    'mkv'
                ])) {
                    $folder_name = '/video/';
                }
                if (in_array($fileParts, ['srt'])) {
                    $folder_name = '/sub/';
                }

                if (in_array($fileParts, $list_allow)) {
                    if (!file_exists($target_dir . $folder . $folder_name)) mkdir($target_dir . $folder . $folder_name, 0777, true);
                    $file_name = md5(uniqid(rand(), true)) . '_' . Helper::removeTitle($upload->getName());
                    $avatar = $folder . $folder_name . $file_name;
                    $upload->moveTo($target_dir . $avatar);

                    $key = !(strpos($upload->getKey(), '.')) ? $upload->getKey() : strstr($upload->getKey(), '.', true);
                    if (in_array($key, $field)) $list_image[$key][] = $avatar;

                }
            }
        }
        if ($return_array) return $list_image;
        else return $avatar;
    }

    public function post_file_multiple($name, $fullpath = false)
    {
        $target_dir = $this->config->media->dir;

        $list_data = [];

        foreach ($_FILES[$name]['tmp_name'] as $key => $tmp_name) {

            if (!$_FILES[$name]['name'][$key]) {
                $list_data[] = "";
                continue;
            }


            $folder = "/uploads/" . date("Y/m/d");

            $list_allow = [
                "jpg",
                "jpeg",
                "png",
                "gif",
                "mp3",
                "mp4"
            ];

            $fileParts = strtolower(pathinfo($_FILES[$name]['name'][$key], PATHINFO_EXTENSION));

            $folder_name = '/general/';

            if (in_array($fileParts, [
                'jpg',
                'jpeg',
                'gif',
                'png'
            ])) {
                $folder_name = '/picture/';
            }

            if (in_array($fileParts, [
                'mp3',
                'mp4',
                'avi',
                'mkv'
            ])) {
                $folder_name = '/video/';
            }

            if (in_array($fileParts, ['srt'])) {
                $folder_name = '/sub/';
            }

            if (!file_exists($target_dir . $folder . $folder_name)) mkdir($target_dir . $folder . $folder_name, 0777, true);
            $target_file = $folder . $folder_name . basename(md5(strtotime("now") . uniqid() . rand(0, 9999)) . "_" . Helper::removeTitle($_FILES[$name]['name'][$key]));

            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (file_exists($target_file)) return null;
            if ($_FILES[$name]['size'][$key] <= 0) return null;
            if (!in_array($imageFileType, $list_allow)) return null;
            move_uploaded_file($_FILES[$name]['tmp_name'][$key], $target_dir . $target_file);
            if ($fullpath == true) $list_data[] = $target_dir . $target_file;
            else $list_data[] = $target_file;

        }

        return $list_data;
    }

    public function checksidebar($sidebaritem)
    {
        $permission = $this->user_info['listpermission'];
        $lp = explode(",", $sidebaritem['key']);
        foreach ($lp as $item) {
            if (in_array($item, $permission)) return 1;
            else return 0;
        }

        return 0;
    }

    public function checkpermission($key, $redirect = true)
    {
        if (Module::is_accept_permission($key) == 0) {
            if ($redirect == true) {
                $this->flash->error("You are not authenticate to use this action! Contact to admin for this problem!");
                $this->response->redirect("/backend/security/message");
            }

            return 0;
        } else return 1;
    }

    public function verify_captcha($captcha)
    {
        $secret = $this->config->google->recaptcha->secret;

        $fields = [
            'secret' => $secret,
            'response' => $captcha,
            'remoteip' => $_SERVER['REMOTE_ADDR']
            //'remoteip' => 'localhost'
        ];
        $ch = curl_init("https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        $rs = curl_exec($ch);
        $response = json_decode($rs);
        curl_close($ch);
        return $response;
    }

    public function encrypt_password($pass)
    {
        return md5(md5($pass));
    }

}