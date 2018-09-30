<?php
/**
 * Created by PhpStorm.
 * User: lemin
 * Date: 7/19/2018
 * Time: 3:21 AM
 */

namespace Module\Client\Controller;


use Common\Ext\Logger;
use Model\BaseModel;
use Model\User;

class AuthController extends ControllerBase
{
    use Logger;

    public function initialize()
    {
        parent::initialize();
        $this->view->setMainView("login");

    }

    /**
     * @throws \Exception
     */
    public function loginAction()
    {
        if ($this->getAuth()) {
            return $this->response->redirect('/');
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
                    return $this->response->redirect("auth/login");
                }
                //</editor-fold>


                $this->setAuth($o);
                $this->flash->output(true);

                //<editor-fold desc="Redirect">
                $url = $this->session->get("url");
                if ($url) {
                    $this->session->remove("url");
                    return $this->response->redirect($url);
                }
                return $this->response->redirect("/");
                //</editor-fold>

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
                return $this->response->redirect("auth/login");
            }

        }

    }

    public function registerAction()
    {
        //<editor-fold desc="Redirect to dashboard if logined">
        if (!empty($this->getAuth()) && $this->getAuth()->id > 0) {
            return $this->response->redirect('/');
        }
        //</editor-fold>


        if ($this->request->isPost()) {
            try {
                $recaptcha = $_POST['g-recaptcha-response'];
                $recaptcha = $this->verify_captcha($recaptcha);
                if (empty($recaptcha->success) || $recaptcha->success != 1) {
                    $this->flash->error("You must verify captcha to do this action");
                    return $this->response->redirect("/auth/register");
                }

                $data = $this->request->getPost();
                $this->view->data = $data;
                $this->session->set("registerData", $data);

                //<editor-fold desc="Validate Username">
                if (!preg_match('/^[A-Za-z0-9]{3,32}$/', $data['username']) || strlen($data['username']) <= 5) {
                    $this->flash->error('Username cannot be special character and length must be more than 6 character');
                    return $this->response->redirect($this->request->getHTTPReferer());
                }
                //</editor-fold>

                //<editor-fold desc="Validate Email">
                $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                if (!preg_match($regex, $data['email'])) {
                    $this->flash->error("Please enter a valid email!");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }
                //</editor-fold>

                //<editor-fold desc="Check Password Length">
                if (strlen($data['password']) <= 5) {
                    $this->flash->error("Password must be longer than 6 characters");
                    return $this->response->redirect($this->request->getHTTPReferer());
                }
                //</editor-fold>

                //<editor-fold desc="Validate Password">
                if ($data['password'] != $data['repassword']) {
                    $this->flash->error('Password not miss match');
                    return $this->response->redirect($this->request->getHTTPReferer());
                }
                //</editor-fold>

                //<editor-fold desc="Check existed Username">
                $c_username = User::count([
                    'conditions' => "username = :u:",
                    'bind' => ['u' => $data['username']]
                ]);
                if ($c_username > 0) {
                    $this->flash->error('Username available');
                    return $this->response->redirect($this->request->getHTTPReferer());
                }
                //</editor-fold>

                //<editor-fold desc="Check existed Email">
                $c_email = User::count([
                    'conditions' => "email = :email:",
                    'bind' => ['email' => $data['email']]
                ]);
                if ($c_email > 0) {
                    $this->flash->error('Email available');
                    return $this->response->redirect($this->request->getHTTPReferer());
                }
                //</editor-fold>

                $data['password'] = $this->encrypt_password($data['password']);
                $data['username'] = strtolower($data['username']);

                //<editor-fold desc="Create user">
                $u = new User();
                $u = $u->map_object($data);
                $u->status = BaseModel::STATUS_ACTIVE;
                $u->role = 0;
                $u->datecreate = time();
                //</editor-fold>

                try {
                    if (!$u->save()) {
                        foreach ($u->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                    $this->setAuth($u);
                    $this->session->remove("registerData");
                    $this->response->redirect('/');
                } catch (\Exception $e) {
                    $this->flash->error($e->getMessage());
                    return $this->response->redirect($this->request->getHTTPReferer());
                }

            } catch (Exception $e) {
                $this->flash->error('Currently the system is handling large volumes of requests. Please try again later. Sincerely thank you for this patience!');
                return $this->response->redirect($this->request->getHTTPReferer());
            }
        }
        $this->view->setVars([
            'data' => $this->session->get("registerData"),
            'header' => [
                'title' => "Register",
                'desc' => "Register",
                'keyword' => "Register"
            ]
        ]);
        if ($this->request->isGet()) {
            if ($this->request->get('email')) {
                $this->view->data['email'] = $this->request->get('email');
            }
        }

    }

    public function logoutAction()
    {
        $this->session->remove("user_info");
        $this->session->remove("list_token");
        $email_cookie = $this->cookies->get("email");
        $email_cookie->delete();

        $password_cookie = $this->cookies->get("password");
        $password_cookie->delete();

        /*$this->session->destroy();
        session_destroy();*/
        return $this->response->redirect("/");
    }
}