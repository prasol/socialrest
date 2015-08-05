<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Repositories\UserRepositoryInterface', 'App\Repositories\Neo\UserRepository');
        $this->app->bind('App\Repositories\FollowingRepositoryInterface', 'App\Repositories\Neo\FollowingRepository');
    }
}