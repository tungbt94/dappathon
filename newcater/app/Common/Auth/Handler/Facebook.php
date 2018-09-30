<?php


namespace Common\Auth\Handler;


use Common\Exception\AuthFailedException;
use Common\Service\User;
use Model\User as UserModel;
use Phalcon\Mvc\Url;

class Facebook extends OAuth
{
    protected $appId;
    protected $appSecret;
    protected $_facebookInstance;

    public function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }


    function handle($credentials)
    {
        if (is_array($credentials) && isset($credentials['state'])) {
            $credentials['state'];
            $helper = $this->getFacebookInstance()->getRedirectLoginHelper();
            $helper->getPersistentDataHandler()->set('state', $credentials['state']);

            $accessToken = $helper->getAccessToken();
        } else {
            $accessToken = $credentials;
        }

        $response = $this->getFacebookInstance()->get('/me?fields=id,name,email,gender,birthday,picture{url}', $accessToken);
        $data = $response->getDecodedBody();

        try {
            return $this->verify($data['email'], []);

        } catch (AuthFailedException $e) {
            if ($e->getCode() == AuthFailedException::USER_NOT_FOUND_CODE) {

                /** @var \Common\Service\User $userService */
                $userService = provider(User::class, ['user', 'user', true]);

                return $userService->registerOrThrow([
                    'email'    => $data['email'],
                    'fullname' => $data['name'],
                    'avatar'    => isset($data['picture']['data']['url']) ? $data['picture']['data']['url'] : null,
                    'gender'    => ($data['gender'] == 'male')
                        ? UserModel::MALE_GENDER
                        : (($data['gender'] == 'female') ? User::FEMALE_GENDER : null),
                ])->toArray();

            }
        }
    }


    static function getName()
    {
        return 'facebook';
    }


    function getLinkLogin($callBackUrl)
    {
        if (!start_with($callBackUrl, 'http')) {
            /** @var Url $url */
            $url = provider('url');
            $callBackUrl = origin_url() . $url->get($callBackUrl);
        }

        $permissions = [
            'public_profile',
            'email',
            'manage_pages',
            'user_posts',
        ];

        return $this->getFacebookInstance()->getRedirectLoginHelper()
            ->getLoginUrl($callBackUrl, $permissions);
    }


    function getFacebookInstance()
    {
        if (!$this->_facebookInstance instanceof \Facebook\Facebook) {
            $this->_facebookInstance = new \Facebook\Facebook([
                'app_id'     => $this->appId,
                'app_secret' => $this->appSecret,
            ]);
        }

        return $this->_facebookInstance;
    }
}