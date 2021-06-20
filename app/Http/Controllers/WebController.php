<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\PlurkAuthenticationController;
use App\Models\PlurkUser\IPlurkUser;
use App\Models\ScheduledPlurk;
use App\Services\PlurkApiService;
use Carbon\CarbonImmutable;
use Carbon\FactoryImmutable;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class WebController extends Controller
{
    public function __invoke(PlurkApiService $plurkApi)
    {
        if ( ! $plurkApi->isAuthorized()) {
            /** @var PlurkAuthenticationController $plurkAuthController */
            $plurkAuthController = App::make(PlurkAuthenticationController::class);
            return $plurkAuthController->__invoke();
        }

        try {
            return $this->getDashboard($plurkApi);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() != 401) {
                throw $e;
            }
            // 401 UNAUTHORIZED
            /** @var PlurkAuthenticationController $plurkAuthController */
            $plurkAuthController = App::make(PlurkAuthenticationController::class);
            return $plurkAuthController->destroy($plurkApi);
        }
    }

    public function postNewPlurk(Request $request, PlurkApiService $plurkApi, IPlurkUser $plurkUser)
    {
        // 須轉換換行字元，否則發噗後會看到多餘空格
        $request->merge([
            'content' => Str::replace("\r\n", "\n", $request->input('content') ?? ''),
        ]);

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
}
