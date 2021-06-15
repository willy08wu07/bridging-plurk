<?php

namespace App\Providers;

use App\Models\PlurkUser\IPlurkUser;
use App\Models\PlurkUser\PlurkUser;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IPlurkUser::class, function (Application $app) {
            return $app->make(PlurkUser::class);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
