<?php


namespace Common\Service;


use Common\Exception\AuthFailedException;
use Common\Exception\Exception;
use Common\Sql\Dao\MySqlDAO;
use Common\Validation\SimpleValidation;
use Model\BaseModel;
use Phalcon\Cache\Backend;
use Phalcon\Di\Service;
use Model\User as UserModel;
use Phalcon\Logger;
use Phalcon\Mailer\Manager;
use Phalcon\Security;
use Phalcon\Text;

class User extends Service
{
    use \Common\Ext\Logger;
    const RSPWD_EXPIRE_IN = 1800;

    /**
     * @param $emailOrId
     *
     * @return \Phalcon\Mvc\Model
     */
    function findUserByEmailOrUsername($emailOrId)
    {
        return UserModel::findFirst([
            'conditions' => 'email = ?0 OR username = ?0',
            'bind'       => [$emailOrId]
        ]);
    }


    /**
     * @param $user_id
     *
     * @return UserModel
     * @throws Exception
     */
    function findUserByIdOrThrow($user_id)
    {
        $user = UserModel::findFirstById($user_id);

        if ($user == null) {
            throw new Exception("Người dùng không tồn tại");
        }

        if (!$this->checkActiveMember($user)) {
            throw new Exception("Người dùng chưa được kích hoạt");
        }

        return $user;
    }


    function checkActiveMember($user)
    {
        if ($user instanceof UserModel) {
            return $user->status == BaseModel::STATUS_ACTIVE;

        } elseif (is_array($user)) {
            return $user['status'] == BaseModel::STATUS_ACTIVE;

        } elseif (is_object($user)) {
            return $user->status == BaseModel::STATUS_ACTIVE;
        }

        return false;
    }


    /**
     * @param $userData
     *
     * @return UserModel
     * @throws Exception
     */
    function registerOrThrow($userData)
    {
        if (is_array($userData)) {
            $userData['status'] = UserModel::STATUS_ACTIVE;
        }

        /** @var UserModel $createdUser */
        $createdUser = $this->createUser($userData);

        if ($messages = $createdUser->getMessages()) {
            throw new Exception($messages[0]->getMessage());
        }
        return $createdUser;
    }


    /**
     * @param $id
     * @param $userInfo
     * @param null $currentPassword
     *
     * @return bool
     * @throws Exception
     */
    function updateUserInfo($id, $userInfo, $currentPassword = null)
    {
        $needVerifyFields = ['username'];
        if (array_intersect_key($needVerifyFields, $userInfo)) {
            $this->verifyUserInfo($id, $currentPassword);
        }

        /** @var UserModel $user */
        $user = new UserModel();
        $user->id = $id;
        $user->assign($userInfo);
        $user->update(null, array_keys($userInfo));

        if ($messages = $user->getMessages()) {
            /** @var Logger\AdapterInterface $logger */
            $this->_logError(sprintf('User %s update info failed: %s', $id, $messages[0]->getMessage()));
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }

        return true;
    }


    /**
     * @param $id
     * @param $userInfo
     * @return bool
     * @throws Exception
     *
     */
    function setUserInfo($id, $userInfo)
    {
        /** @var UserModel $user */
        $user = new UserModel();
        $user->id = $id;
        $user->assign($userInfo);

        /** @var Security $security */
        $security = provider('security');

        if($user->password){
            $user->password = $security->hash($user->password);
        }

        $user->update(null, array_keys($userInfo));

        if ($messages = $user->getMessages()) {
            /** @var Logger\AdapterInterface $logger */
            $this->_logError(sprintf('User %s update info failed: %s', $id, $messages[0]->getMessage()));
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }

        return true;
    }


    /**
     * @param $userData
     *
     * @param bool $overwrite
     *
     * @return \Model\User
     */
    function createUser($userData, $overwrite = false)
    {
        if (!$userData instanceof UserModel) {
            $userData = UserModel::newInstance($userData);
        }
        /** @var Security $security */
        $security = provider('security');

        $userData->assign([
            'datecreate' => time(),
            'password'   => $userData->password ? $security->hash($userData->password) : null
        ]);

        if ($overwrite) {
            $userData->save();

        } else {
            $userData->create();
        }

        return $userData;
    }


    /**
     * @param $userData
     *
     * @param bool $overwrite
     *
     * @return \Model\User
     */
    function deleteUserByIds($ids)
    {
        if (is_string($ids)) {
            $ids = explode(',', str_replace(' ', '', $ids));
        }

        $dao = new MySqlDAO(UserModel::class);
        return $dao->deleteConditions([
            'conditions' => 'id IN ({0:array})',
            'bind'       => [$ids]
        ], false, true);
    }


    /**
     * @param $id
     * @param $currentPassword
     * @param $newPassword
     *
     * @return bool
     * @throws Exception
     */
    function changePassword($id, $currentPassword, $newPassword)
    {
        $verifyResult = $this->verifyUserInfo($id, $currentPassword);
        return $verifyResult ? $this->setPassword($id, $newPassword) : false;
    }


    /**
     * @param $id
     * @param $currentPassword
     *
     * @return bool
     * @throws Exception
     */
    function verifyUserInfo($id, $currentPassword)
    {
        $user = UserModel::findFirstById($id);
        if (!$user) {
            throw new Exception("Người dùng không tồn tại");
        }

        if (!$this->checkActiveMember($user)) {
            throw new Exception("Người dùng chưa kích hoạt");
        }

        /** @var Security $security */
        $security = provider('security');
        if ($user->password && !$security->checkHash($currentPassword, $user->password)) {
            throw new Exception("Mật khẩu không đúng");
        }

        return true;
    }


    /**
     * @param $id
     * @param $newPassword
     *
     * @return bool
     * @throws Exception
     */
    function setPassword($id, $newPassword)
    {
        $user = UserModel::findFirstById($id);;

        if ($user == null) {
            throw new Exception("Người dùng không tồn tại");
        }

        /** @var Security $security */
        $security = provider('security');
        $user->password = $security->hash($newPassword);

        $user->update(null, ['password']);
        if ($messages = $user->getMessages()) {
            /** @var Logger\AdapterInterface $logger */
            $this->_logError(sprintf('User %s change password failed: %s', $id, $messages[0]->getMessage()));
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }

        return true;
    }


    function requestResetPassword($email)
    {
        /** @var \Model\User $user */
        $user = $this->findUserByEmailOrUsername($email);
        if ($user == null) {
            throw new Exception("Người dùng không tồn tại");
        }

        $resetCode = Text::random(Text::RANDOM_ALPHA, 8);
        $recovery = Text::random(Text::RANDOM_ALNUM, 5);

        $resetPasswordObject = [
            'hashed_code' => md5($resetCode),
            'since'       => time(),
            'recovery'    => $recovery
        ];


        /** @var Backend $cache */
        $cache = provider('cache');

        $key = "rspwd_{$user->id}";
        $r = $cache->save($key, $resetPasswordObject, static::RSPWD_EXPIRE_IN);
        if (!$r) {
            $this->_logError("Can not write to cache: " . $key);
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại sau");
        }

        /** @var Manager $mailer */
        $mailer = provider('mailer');
        $recoveryId = base64_encode(sprintf("%s=%s", $recovery, $user->id));

        $res = $mailer->createMessage()
            ->subject('Đặt lại mật khẩu')
            ->content('Vui lòng click vào <b><a href="' . origin_url() . '/user/auth/recovery_password?recovery_id=' . urlencode($recoveryId) . '&code=' . $resetCode . '">đây</a></b> để đặt lại mật khẩu')
//            ->content('Mã khôi phục mật khẩu của bạn là: ' . $resetCode . '. Link reset mật khẩu: ' . origin_url() . '/user/auth/recovery_password?recovery_id=' . urlencode($recoveryId) . '&code=' . $resetCode)
            ->from('noreply@phimhay.vn', 'PhimHay')
            ->to($email)
            ->send();

        return $res;
    }


    function recoveryPassword($newPassword, $recoveryId, $code)
    {
        $decodedRecovery = base64_decode($recoveryId, true);

        if ($decodedRecovery == false) {
            throw new AuthFailedException("Invalid recoveryId");
        }

        list($recovery, $userId) = explode('=', $decodedRecovery);

        if ($userId == null) {
            throw new AuthFailedException("Invalid recoveryId");
        }

        /** @var Backend $cache */
        $cache = provider('cache');

        $resetPasswordObject = $cache->get("rspwd_$userId");

        if ($resetPasswordObject == null) {
            throw new AuthFailedException("Corrupted resetPasswordObject");
        }

        $match = ($resetPasswordObject['hashed_code'] == md5($code))
            && ($resetPasswordObject['recovery'] == $recovery);

        if (!$match) {
            throw new AuthFailedException("Invalid code or recovery");
        }

        if (time() - $resetPasswordObject['since'] > 1800) {
            throw new AuthFailedException("Code has been expired");
        }

        $r = $this->setPassword($userId, $newPassword);
        if ($r) {
            $cache->delete("rspwd_$userId");
        }

        return $r;
    }


    function verifyResetPassword($email, $code)
    {
        /** @var \Model\User $user */
        $user = $this->findUserByEmailOrUsername($email);
        if ($user == null) {
            throw new Exception("Người dùng không tồn tại");
        }

        $resetCode = Text::random(Text::RANDOM_ALPHA, 8);
        $resetPasswordObject = [
            'hashedCode'  => md5($resetCode),
            'expiredTime' => time() + static::RSPWD_EXPIRE_IN,
        ];

        /** @var Backend $cache */
        $cache = provider('cache');

        $key = "rspwd_$email";
        $r = $cache->save($key, $resetPasswordObject, static::RSPWD_EXPIRE_IN);
        if (!$r) {
            $this->_logError("Can not write to cache: " . $key);
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại sau");
        }

        /** @var Manager $mailer */
        $mailer = provider('mailer');

        $res = $mailer->createMessage()
            ->subject('Đặt lại mật khẩu')
            ->content('Mã khôi phục mật khẩu của bạn là: ' . $resetCode)
            ->from('noreply@phimhay.vn', 'PhimHay')
            ->to($email)
            ->send();

        return $res;
    }


    function validateCreateUser($createUserDto)
    {
        $description = [
            'fullname' => 'length:5,30',
            # must be an email format
            # must be unique under 'users' table
            'email'    => 'required | email',
            'age'      => 'numeric',
//            'avatar'             => 'url',
            'username' => 'regex:/^[a-zA-Z0-9_.]{4,20}$/',

            # the format must be 'm-Y.d H:i'
//            'caption'            => 'alnum | length:0, 50',

            # the provided phone number should follow the format
            'phone'    => 'regex:/^\d{7,12}$/',
            'password' => 'required | confirm: re_password | length: 6, 25',
        ];


        $customMessage = [
            'username'         => 'Tên đăng nhập không hợp lệ',
            'email'            => 'Địa chỉ email không hợp lệ',
            'phone'            => 'Số điện thoại không hợp lệ',
            'password.confirm' => 'Mật khẩu không trùng với mật khẩu nhập lại',
            'password.length'  => 'Mật khẩu phải có độ dài 6 - 25 kí tự'
        ];

        // Validation throw exception on failure
        return SimpleValidation::validateOrFail($description, $createUserDto, $customMessage);
    }


    function validateUpdateUser($createUserDto)
    {
        $description = [
            'fullname' => 'length:5,30',
            # must be an email format
            # must be unique under 'users' table
            'email'    => 'email',
//            'avatar'             => 'url',
            'username' => 'regex:/^[a-zA-Z0-9_.]{4,20}$/',

            # the format must be 'm-Y.d H:i'
//            'caption'            => 'alnum | length:0, 50',

            # the provided phone number should follow the format
            'phone'    => 'regex:/^\d{7,12}$/',
        ];


        $customMessage = [
            'username'         => 'Tên đăng nhập không hợp lệ',
            'email'            => 'Địa chỉ email không hợp lệ',
            'phone'            => 'Số điện thoại không hợp lệ',
            'password.confirm' => 'Mật khẩu không trùng với mật khẩu nhập lại',
            'password.length'  => 'Mật khẩu phải có độ dài 6 - 25 kí tự'
        ];

        // Validation throw exception on failure
        return SimpleValidation::validateOrFail($description, $createUserDto, $customMessage);
    }
}














