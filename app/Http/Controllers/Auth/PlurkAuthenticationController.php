<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PlurkUser\IPlurkUser;
use App\Models\User;
use App\Services\PlurkApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(Request $request, PlurkApiService $plurkApi, IPlurkUser $plurkUser)
    {
        $plurkApi->savePermanentUserToken($request['oauth_verifier']);
        $myInfo = $plurkApi->getMyInfo();
        /** @var User $user */
        $user = User::with('plurkTokens')->firstOrCreate([
            'name' => $myInfo['nick_name'],
        ]);
        $user->plurkTokens()->updateOrCreate([], [
            'token' => $plurkUser->getUserToken(),
            'token_secret' => $plurkUser->getUserTokenSecret(),
        ]);
        $user->refresh();
        Auth::login($user, true);
        return redirect()->route('dashboard');
    }

    public function destroy(PlurkApiService $plurkApi)
    {
        $plurkApi->logOut();
        Auth::logout();
        return redirect()->route('login');
    }
}
