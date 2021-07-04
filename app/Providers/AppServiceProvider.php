<?php

namespace App\Providers;

use App\Contracts\Services\CompanySettingContract;
use App\Contracts\Services\UserAuthContract;
use App\Services\CompanySettingService;
use App\Services\UserAuthService;
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
        $this->app->bind(UserAuthContract::class,UserAuthService::class);
        $this->app->bind(CompanySettingContract::class,CompanySettingService::class);
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
