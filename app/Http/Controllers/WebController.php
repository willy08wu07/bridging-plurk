<?php

namespace App\Http\Controllers;

use App\Services\PlurkApiService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function __invoke(PlurkApiService $plurkApi)
    {
        if ( ! $plurkApi->isAuthorized()) {
            return view('plurk-login');
        }

        try {
            return $this->getDashboard($plurkApi);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() != 401) {
                throw $e;
            }
            // 401 UNAUTHORIZED
            return $this->logOut($plurkApi);
        }
    }

    public function postNewPlurk(Request $request, PlurkApiService $plurkApi)
    {
        $plurkApi->postNewPlurk($request->input('content'), $request->input('qualifier') ?? '');
        return redirect()->route('dashboard');
    }

    public function getDashboard(PlurkApiService $plurkApi)
    {
        $myInfo = $plurkApi->getMyInfo();
        $myLatestPlurk = $plurkApi->getMyPlurks(1);
        return view('dashboard', [
            'plurkDisplayName' => $myInfo['display_name'],
            'plurkUserId' => $myInfo['nick_name'],
            'plurkAvatarUrl' => $myInfo['avatar_medium'],
            'latestPlurk' => $myLatestPlurk,
        ]);
    }

    public function logIn(PlurkApiService $plurkApi)
    {
        $loginUrl = $plurkApi->logIn();
        return redirect()->away($loginUrl);
    }

    public function authorizeByPlurk(Request $request, PlurkApiService $plurkApi)
    {
        $plurkApi->savePermanentUserToken($request['oauth_verifier']);
        return redirect()->route('dashboard');
    }

    public function logOut(PlurkApiService $plurkApi)
    {
        $plurkApi->logOut();
        return redirect()->route('dashboard');
    }
}
