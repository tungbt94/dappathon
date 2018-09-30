<?php
/**
 * Created by PhpStorm.
 * User: Dat PC
 * Date: 4/26/2018
 * Time: 10:12 PM
 */

namespace Module\Admin\Controller;


use Common\Util\Helper;
use http\Env\Request;
use Model\Registry;

class RegistryController extends ControllerInit
{

    public function initialize()
    {
        parent::initialize();
        $this->view->header = [
            'title' => 'Cài đặt',
            'description' => 'Cài đặt',
            'keyword' => 'Cài đặt'
        ];


    }

    public function indexAction()
    {
        $object = Registry::findFirst();

        if ($this->request->isPost()) {
            try {
                $data_post = $this->getPost("slogan, hot_line, script_body,script_head,link_facebook ,link_google, link_twitter, link_youtube, seo_title, seo_keyword, seo_description");
                $data_post['map'] = $this->request->getPost('map');
                $data_post['address'] = $this->request->getPost('address');
                // <editor-fold desc="Validate">
                $sale_img = $this->post_file_key("sale_img");
                if ($sale_img != null) $data_post['sale_img'] = $sale_img;

                $logo = $this->post_file_key("logo");
                if ($logo != null) $data_post['logo'] = $logo;

                $favicon = $this->post_file_key("favicon");
                if ($favicon != null) $data_post['favicon'] = $favicon;

                if (!$object->id) $object = new Registry();
                $object->map_object($data_post);
                // </editor-fold>

                if ($object->save()) $this->flash->success("Thao tác thành công!");
                else $this->flash->error($object->getMessages());
            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        $this->view->setVars(compact('object'));
    }

    public function slideAction()
    {

        $object = Registry::findFirst();

        if ($this->request->isPost()) {

            try {

                $name = $this->request->getPost('name');
                $link = $this->request->getPost('link');

                //<editor-fold desc="Image">
                $list_slide = $this->post_file_to_array('slide');
                $slide = !$list_slide['slide'] ? [] : $list_slide['slide'];
                $media = $this->request->getPost('media');
                $media == null && $media = [];
                $data_slide = array_merge($slide, $media);

                $save_slide = [];
                foreach ($data_slide as $key => $item) {
                    $save_slide[] = [
                        'avatar' => $item,
                        'name' => $name[$key],
                        'link' => $link[$key]
                    ];
                }

                $data_post['slide'] = json_encode($save_slide);
                //</editor-fold>


                // <editor-fold desc="Validate">
                if (!$object->id) $object = new Registry();
                $object->map_object($data_post);
                // </editor-fold>


                if ($object->save()) $this->flash->success("Thao tác thành công!");
                else $this->flash->error("Có lỗi xảy ra!");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        $o = Registry::findFirst();
        $this->view->object = $o;
    }

    public function team_supportAction()
    {

        $object = Registry::findFirst();

        if ($this->request->isPost()) {

            try {

                $name = $this->request->getPost('name');
                $email = $this->request->getPost('email');
                $phone = $this->request->getPost('phone');

                //<editor-fold desc="Image">
                $list_team = $this->post_file_to_array('team_support');
                $list_team = !$list_team['team_support'] ? [] : $list_team['team_support'];
                $media = $this->request->getPost('media');
                $media == null && $media = [];
                $data_slide = array_merge($list_team, $media);

                $save_team = [];
                foreach ($data_slide as $key => $item) {
                    $save_team[] = [
                        'avatar' => $item,
                        'name' => $name[$key],
                        'email' => $email[$key],
                        'phone' => $phone[$key]
                    ];
                }

                $data_post['team_support'] = json_encode($save_team);
                //</editor-fold>


                // <editor-fold desc="Validate">
                if (!$object->id) $object = new Registry();
                $object->map_object($data_post);
                // </editor-fold>


                if ($object->save()) $this->flash->success("Thao tác thành công!");
                else $this->flash->error("Có lỗi xảy ra!");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        $o = Registry::findFirst();
        $this->view->object = $o;
    }

    public function shipAction()
    {

        $object = Registry::findFirst();

        if ($this->request->isPost()) {

            try {

                $name = $this->request->getPost('name');
                $des = $this->request->getPost('des');
                $icon= $this->request->getPost('icon');

                //<editor-fold desc="Image">
                $save_ship = [];
                foreach ($icon as $key => $item) {
                    $save_ship[] = [
                        'icon' => $item,
                        'name' => $name[$key],
                        'des' => $des[$key]
                    ];
                }

                $data_post['ship'] = json_encode($save_ship);
                //</editor-fold>


                // <editor-fold desc="Validate">
                if (!$object->id) $object = new Registry();
                $object->map_object($data_post);
                // </editor-fold>


                if ($object->save()) $this->flash->success("Thao tác thành công!");
                else $this->flash->error("Có lỗi xảy ra!");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        $o = Registry::findFirst();
        $this->view->object = $o;
    }

    public function partnerAction()
    {

        $object = Registry::findFirst();

        if ($this->request->isPost()) {

            try {

                $name = $this->request->getPost('name');
                $link = $this->request->getPost('link');

                //<editor-fold desc="Image">
                $list_slide = $this->post_file_to_array('slide');
                $slide = !$list_slide['slide'] ? [] : $list_slide['slide'];
                $media = $this->request->getPost('media');
                $media == null && $media = [];
                $data_slide = array_merge($slide, $media);

                $save_slide = [];
                foreach ($data_slide as $key => $item) {
                    $save_slide[] = [
                        'avatar' => $item,
                        'name' => $name[$key],
                        'link' => $link[$key]
                    ];
                }

                $data_post['partner'] = json_encode($save_slide);
                //</editor-fold>


                // <editor-fold desc="Validate">
                if (!$object->id) $object = new Registry();
                $object->map_object($data_post);
                // </editor-fold>


                if ($object->save()) $this->flash->success("Thao tác thành công!");
                else $this->flash->error("Có lỗi xảy ra!");

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        $o = Registry::findFirst();
        $this->view->object = $o;
    }
}