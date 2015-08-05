<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use App\Extensions\UserProvider;
use Vinelab\NeoEloquent\Connection;
use Vinelab\NeoEloquent\Schema\Grammars\CypherGrammar;
use App\Extensions\NeoEloquent\CypherGrammar as AppCypherGrammar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Auth::extend('simple', function($app) {
            $model = config('auth.model');
            return new UserProvider(new $model());
        });

        $this->app['db']->extend('neo4j', function($config)
        {
            $conn = new Connection($config);
            $conn->setSchemaGrammar(new CypherGrammar());
            $conn->setQueryGrammar(new AppCypherGrammar());
            return $conn;
        });
    }
}
