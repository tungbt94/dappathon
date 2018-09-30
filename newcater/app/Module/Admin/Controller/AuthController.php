<?php
/**
 * Created by PhpStorm.
 * User: lemin
 * Date: 7/1/2018
 * Time: 11:17 PM
 */

namespace Module\Admin\Controller;


use Common\Util\Helper;
use Model\BaseModel;
use Model\User;

class AuthController extends ControllerInit
{
    public function initialize()
    {
        parent::initialize();
        $this->view->setMainView("login");
        $this->view->header = [
            'title' => 'Authorize',
            'description' => 'Authorize',
            'keyword' => 'Authorize'
        ];
    }

    public function loginAction()
    {
        $auth = $this->getAuth();
        if (!empty($auth) && $auth->id > 0) {
            return $this->response->redirect('dashboard');
        }

        if ($this->request->isPost()) {
            try {

                //<editor-fold desc="Check Recaptcha Google">
                $recaptcha = $_POST['g-recaptcha-response'];
                $recaptcha = $this->verify_captcha($recaptcha);
                if (empty($recaptcha->success) || $recaptcha->success != 1) {
                    $this->flash->error("You must verify captcha to do this action");
                    return $this->response->redirect("/auth/login");
                }
                //</editor-fold>

                //<editor-fold desc="Encrypt Password">
                $data = $this->request->getPost();
                $data['password'] = $this->encrypt_password($data['password']);
                //</editor-fold>

                //<editor-fold desc="Find User">
                $o = User::findFirst([
                    'conditions' => "(username = :u: or email = :u:) and password = :p:",
                    'bind' => [
                        'u' => $data['username'],
                        'p' => $data['password']
                    ]
                ]);
                //</editor-fold>

                //<editor-fold desc="User not exist">
                if (!$o) {
                    $this->flash->error("Account not exists!");
                    return $this->response->redirect("/auth/login");
                }
                //</editor-fold>

                //<editor-fold desc="Check Role">
                if ($o->role != 1) {
                    $this->flash->error("You have no permissions");
                    return $this->response->redirect("/auth/login");
                }
                //</editor-fold>

                //<editor-fold desc="Save Auth">
                $this->setAuth($o);
                //</editor-fold>

                //<editor-fold desc="Redirect">
                $url = $this->session->get("url");
                if ($url) {
                    $this->session->remove("url");
                    return $this->response->redirect($url);
                }
                return $this->response->redirect("dashboard/index");
                //</editor-fold>

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
                return $this->response->redirect("auth/login");
            }
        }

    }

    /**
     * Logout
     */
    public function logoutAction()
    {
        $this->session->destroy();
        session_destroy();
        return $this->response->redirect("auth/login");
    }
}