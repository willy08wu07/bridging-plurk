<?php

namespace App\Services;

use App\Models\PlurkUser;
use Qlurk\ApiClient;
use Qlurk\Oauth;

class PlurkApiService
{
    private $plurkUser;
    private $consumerKey;
    private $consumerSecret;

    public function __construct(PlurkUser $plurkUser)
    {
        $this->plurkUser = $plurkUser;
        $this->consumerKey = env('PLURK_CONSUMER_KEY');
        $this->consumerSecret = env('PLURK_CONSUMER_SECRET');
    }

    public function postNewPlurk(string $content, string $qualifier = '')
    {
        $client = $this->getApiClient();
        $response = $client->call('/APP/Timeline/plurkAdd', [
            'content' => $content,
            'qualifier' => $qualifier,
            // 中文（臺灣）
            'lang' => 'tr_ch',
        ]);
        return $response;
    }

    public function getMyPlurks(int $limit = 20)
    {
        $client = $this->getApiClient();
        $response = $client->call('/APP/Timeline/getPlurks', [
            'limit' => $limit,
            'filter' => 'my',
        ]);
        return $response;
    }

    public function getMyInfo()
    {
        $client = $this->getApiClient();
        $response = $client->call('/APP/Users/me');
        return $response;
    }

    public function logIn()
    {
        $client = $this->getApiClient();
        $oAuth = new Oauth($client);
        $response = $oAuth->getRequestToken();
        $token = $response['oauth_token'];
        $tokenSecret = $response['oauth_token_secret'];
        $this->plurkUser->setOAuthUserToken($token, $tokenSecret);
        return "https://www.plurk.com/OAuth/authorize?oauth_token={$token}";
    }

    public function logOut()
    {
        $this->plurkUser->clearUserToken();
    }

    public function isAuthorized()
    {
        return $this->plurkUser->isAuthorized();
    }

    public function savePermanentUserToken(string $verifier)
    {
        $client = $this->getApiClient();
        $oAuth = new Oauth($client);
        $response = $oAuth->getAccessToken(
            $verifier,
            $this->plurkUser->getUserToken(),
            $this->plurkUser->getUserTokenSecret()
        );
        $this->plurkUser->setPermanentUserToken(
            $response['oauth_token'],
            $response['oauth_token_secret']
        );
    }

    private function getApiClient()
    {
        return $this->plurkUser->isAuthorized() ?
            $this->getUserApiClient() :
            $this->getAppApiClient();
    }

    private function getAppApiClient()
    {
        return new ApiClient(
            $this->consumerKey,
            $this->consumerSecret
        );
    }

    private function getUserApiClient()
    {
        return new ApiClient(
            $this->consumerKey,
            $this->consumerSecret,
            $this->plurkUser->getUserToken(),
            $this->plurkUser->getUserTokenSecret()
        );
    }
}
