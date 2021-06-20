<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PlurkApiService;
use Illuminate\Http\Request;

class PlurkAuthenticationController extends Controller
{
    public function __invoke()
    {
        return view('plurk-login');
    }

    public function redirectToPlurkOAuth(PlurkApiService $plurkApi)
    {
        $loginUrl = $plurkApi->logIn();
        return redirect()->away($loginUrl);
    }

    public function store(Request $request, PlurkApiService $plurkApi)
    {
        $plurkApi->savePermanentUserToken($request['oauth_verifier']);
        return redirect()->route('dashboard');
    }

    public function destroy(PlurkApiService $plurkApi)
    {
        $plurkApi->logOut();
        return redirect()->route('dashboard');
    }
}
