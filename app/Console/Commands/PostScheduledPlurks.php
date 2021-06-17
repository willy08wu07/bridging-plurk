<?php

namespace App\Console\Commands;

use App\Models\PlurkUser\PlurkUserFromToken;
use App\Models\ScheduledPlurk;
use App\Services\PlurkApiService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PostScheduledPlurks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plurk:post-scheduled-plurks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post scheduled plurks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var Collection<int, ScheduledPlurk> */
        $plurks = ScheduledPlurk::where('scheduled_time', '<=', now())->get();
        foreach ($plurks as $plurk) {
            /** @var PlurkApiService */
            $plurkApi = App::makeWith(PlurkApiService::class, [
                'plurkUser' => new PlurkUserFromToken($plurk->token, $plurk->token_secret),
            ]);
            DB::transaction(function () use ($plurkApi, $plurk) {
                $plurk->delete();
                $plurkApi->postNewPlurk($plurk->content, $plurk->qualifier);
            });
        }
        return 0;
    }
}
