<?php

namespace App\Http\Controllers;

use App\Models\PlurkUser;
use App\Models\ScheduledPlurk;
use App\Services\PlurkApiService;
use Carbon\CarbonImmutable;
use Carbon\FactoryImmutable;
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

    public function postNewPlurk(Request $request, PlurkApiService $plurkApi, PlurkUser $plurkUser)
    {
        $reqParams = $request->validate([
            'qualifier' => 'nullable|string',
            'content' => 'required|string',
            'scheduled_date' => 'required|date_format:Y-m-d',
            'scheduled_time' => 'required|date_format:H:i',
        ]);
        $scheduledDateTime = "{$reqParams['scheduled_date']} {$reqParams['scheduled_time']}";
        $scheduledDateTime = CarbonImmutable::createFromTimeString($scheduledDateTime, 'Asia/Taipei')
            ->setTimezone('+00:00');
        if ($scheduledDateTime->isPast()) {
            $plurkApi->postNewPlurk($reqParams['content'], $reqParams['qualifier'] ?? '');
            return redirect()->route('dashboard');
        }
        $scheduledPlurk = new ScheduledPlurk();
        $scheduledPlurk->qualifier = $reqParams['qualifier'] ?? '';
        $scheduledPlurk->content = $reqParams['content'];
        $scheduledPlurk->token = $plurkUser->getUserToken();
        $scheduledPlurk->token_secret = $plurkUser->getUserTokenSecret();
        $scheduledPlurk->scheduled_time = $scheduledDateTime;
        $scheduledPlurk->save();
        return redirect()->route('dashboard');
    }

    public function getDashboard(PlurkApiService $plurkApi)
    {
        $carbonFactory = new FactoryImmutable([
            'locale' => 'zh_TW',
            'timezone' => 'Asia/Taipei',
        ]);
        $myInfo = $plurkApi->getMyInfo();
        $myLatestPlurk = $plurkApi->getMyPlurks(1);
        return view('dashboard', [
            'plurkDisplayName' => $myInfo['display_name'],
            'plurkUserId' => $myInfo['nick_name'],
            'plurkAvatarUrl' => $myInfo['avatar_medium'],
            'latestPlurk' => $myLatestPlurk,
            'now' => $carbonFactory->now(),
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
