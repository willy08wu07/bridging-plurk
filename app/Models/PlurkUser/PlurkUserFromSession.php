<?php

namespace App\Models\PlurkUser;

use Illuminate\Http\Request;

class PlurkUserFromSession implements IPlurkUser
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getUserToken()
    {
        return $this->request->session()->get('token');
    }

    public function getUserTokenSecret()
    {
        return $this->request->session()->get('tokenSecret');
    }

    public function isAuthorized()
    {
        return $this->request->session()->exists('isAuthorized');
    }

    public function setOAuthUserToken(string $token, string $tokenSecret)
    {
        $this->clearUserToken();
        $this->request->session()->put([
            'token' => $token,
            'tokenSecret' => $tokenSecret,
        ]);
    }

    public function setPermanentUserToken(string $token, string $tokenSecret)
    {
        $this->clearUserToken();
        $this->request->session()->put([
            'token' => $token,
            'tokenSecret' => $tokenSecret,
            'isAuthorized' => true,
        ]);
    }

    public function clearUserToken()
    {
        $this->request->session()->invalidate();
    }
}
