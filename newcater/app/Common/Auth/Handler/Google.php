<?php


namespace Common\Auth\Handler;


use Common\Exception\AuthFailedException;
use Common\Exception\Exception;
use Common\Service\User;
use Google_Client;
use Model\User as UserModel;
use Phalcon\Mvc\Url;

class Google extends OAuth
{
    protected $clientId;
    protected $clientSecret;
    protected $_googleClient;


    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }


    function handle($credentials)
    {
        $googleClient = $this->getGoogleClient();

        if (is_array($credentials) && $credentials['code']) {
            $accessToken = $googleClient->fetchAccessTokenWithAuthCode($credentials['code']);

        } else {
            $accessToken = $credentials;
        }

        $googleClient->setAccessToken($accessToken);
        $oauthService = new \Google_Service_Oauth2($googleClient);

        $userInfo = $oauthService->userinfo->get();

        try {
            return $this->verify($userInfo->getEmail(), []);

        } catch (AuthFailedException $e) {
            if ($e->getCode() == AuthFailedException::USER_NOT_FOUND_CODE) {

                /** @var \Common\Service\User $userService */
                $userService = provider(User::class, ['user', 'user', true]);

                return $userService->registerOrThrow([
                    'email'    => $userInfo->getEmail(),
                    'fullname' => $userInfo->getName(),
                    'avatar'    => $userInfo->getPicture(),
                    'gender'    => ($userInfo->getGender() == 'male')
                        ? UserModel::MALE_GENDER
                        : (($userInfo->getGender() == 'female') ? User::FEMALE_GENDER : null),
                ])->toArray();

            } else {
                throw $e;
            }
        }
    }


    static function getName()
    {
        return 'google';
    }


    function getLinkLogin($urlCallBack)
    {
        $client = $this->getGoogleClient($urlCallBack);
        return $client->createAuthUrl();
    }


    function getGoogleClient($urlCallBack = 'auth/callback/google')
    {

        if (!$this->_googleClient instanceof Google_Client) {

            if (!start_with($urlCallBack, 'http')) {
                /** @var Url $url */
                $url = provider('url');
                $urlCallBack = origin_url() . $url->get($urlCallBack);
            }

            $client = new Google_Client();
            $client->setClientId($this->clientId);
            $client->setClientSecret($this->clientSecret);
            $client->setAccessType("offline");        // offline access
            $client->setIncludeGrantedScopes(true);   // incremental auth
            $client->addScope('https://www.googleapis.com/auth/userinfo.email');
            $client->addScope('https://www.googleapis.com/auth/userinfo.profile');
            $client->setRedirectUri($urlCallBack);

            $this->_googleClient = $client;
        }

        return $this->_googleClient;
    }
}