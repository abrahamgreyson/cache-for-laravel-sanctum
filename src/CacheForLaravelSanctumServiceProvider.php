<?php

namespace CacheForLaravelSanctum;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class CacheForLaravelSanctumServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
