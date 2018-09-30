<?php

namespace Module\Client\Controller;

use Common\Controller\Base;
use Common\Util\Arrays;
use Common\Util\Helper;
use Common\Util\Pql;
use Model\BaseModel;
use Model\Category;
use Model\Menu;
use Model\Project;
use Model\Registry;
use Model\User;

class ControllerBase extends Base
{
    public function initialize()
    {
        $controller = $this->router->getControllerName();
        if (!$this->request->isAjax() && $controller != 'auth' && $controller != 'dmz') {
            $url = actual_request();
            $this->session->set('url', $url);
        }

        $user_info = $this->getAuth();
        $this->view->setVars([
            'user_info' => $user_info,
            'time' => time()
        ]);

    }

    public function setheader($title = "", $desc = "", $keyword = "", $imagepath = "", $currenturl = "")
    {
        $header = new \stdClass();
        $header->seo_title = $title;
        $header->seo_description = $desc;
        $header->seo_keyword = $keyword;
        $header->imagepath = $imagepath;
        $header->currenturl = $currenturl;

        $this->view->header = $header;

    }

    public function setAuth($user_info)
    {
        $this->session->set('user_info', $user_info);
    }

    /**
     * @return User
     */
    public function getAuth()
    {
        return $this->session->get('user_info');
    }

    /**
     * @return null|User
     */
    public function getUserInfo()
    {
        if (!empty($this->getAuth())) {
            $auth = $this->getAuth();
            $user_info = User::findFirst($auth->id);
            $this->view->user_info = $user_info;
            return $user_info;
        } else return null;
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

    public function showDebug()
    {
        ini_set("display_errors", 1);
        error_reporting(E_ALL);
    }

    public function encrypt_password($pass)
    {
        return md5(md5($pass));
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

    public function setDataJson($status, $data, $message)
    {
        $this->view->disable();
        header("Content-Type:application/json;charset=utf-8");
        return json_encode([
            'status' => $status,
            'data' => $data,
            'message' => $message
        ]);
    }

    public function render_template($controller, $action, $data = null)
    {
        $view = $this->view;

        $content = $view->getRender($controller, $action, ["object" => $data], function ($view) {
            $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
        });
        return $content;
    }

}
