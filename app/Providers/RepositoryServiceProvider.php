<?php

namespace App\Providers;

use App\Contracts\Repositories\CompanyRepository;
use App\Contracts\Repositories\CompanySettingRepository;
use App\Contracts\Repositories\PasswordResetRepository;
use App\Contracts\Repositories\UserRepository;
use App\Repositories\CompanyRepositoryEloquent;
use App\Repositories\CompanySettingRepositoryEloquent;
use App\Repositories\PasswordResetRepositoryEloquent;
use App\Repositories\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CompanyRepository::class, CompanyRepositoryEloquent::class);
        $this->app->bind(CompanySettingRepository::class, CompanySettingRepositoryEloquent::class);
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(PasswordResetRepository::class, PasswordResetRepositoryEloquent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
