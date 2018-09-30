<?php


namespace Common\Auth\Handler;


use Assert\Assertion;
use Common\Exception\AuthFailedException;
use Common\Ext\Logger;
use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Response\StatusCode;
use Phalcon\Mvc\User\Component;

class Id extends Component implements Handler
{
    use PersistentVerifyExt;
    use Logger;

    function handle($idToken)
    {
        Assertion::notEmpty($idToken, 'Thiếu token id xác thực');

        $curl = new Curl();
        $curl->setBaseUri(config('app:id_url'));
        $curl->setOptions([
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT        => 3
        ]);

        $response = $curl->get('/auth/verify?id_token=' . $idToken);

        if ($response->header->statusCode != StatusCode::OK) {
            $this->_logDebug(sprintf("Id Auth error: %s %s", $response->header->status, $response->body), 'auth');
            throw new AuthFailedException("Lỗi xác thực id");
        }

        if (!$data = json_decode($response->body, true)) {
            $this->_logDebug(sprintf("Id Auth error, invalid json body: %s", $response->body), 'auth');
            throw new AuthFailedException("Lỗi xác thực id");
        }

        $user = $this->verify($data['username'] ?: $data['email'], function () {
            return true;
        });

        return $user;
    }


    static function getName()
    {
        return 'id';
    }
}