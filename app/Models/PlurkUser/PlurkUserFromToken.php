<?php

namespace App\Models\PlurkUser;

class PlurkUserFromToken implements IPlurkUser
{
    private $token;
    private $tokenSecret;
    private $isAuthorized;

    public function __construct(string $token, string $tokenSecret)
    {
        $this->setPermanentUserToken($token, $tokenSecret);
    }

    public function getUserToken()
    {
        return $this->token;
    }

    public function getUserTokenSecret()
    {
        return $this->tokenSecret;
    }

    public function isAuthorized()
    {
        return isset($this->isAuthorized);
    }

    public function setOAuthUserToken(string $token, string $tokenSecret)
    {
        $this->clearUserToken();
        $this->token = $token;
        $this->tokenSecret = $tokenSecret;
    }

    public function setPermanentUserToken(string $token, string $tokenSecret)
    {
        $this->clearUserToken();
        $this->token = $token;
        $this->tokenSecret = $tokenSecret;
        $this->isAuthorized = true;
    }

    public function clearUserToken()
    {
        $this->token = null;
        $this->tokenSecret = null;
        $this->isAuthorized = null;
    }
}
