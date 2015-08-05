<?php

namespace App\Extensions\Neo;

use Vinelab\NeoEloquent\NeoEloquentServiceProvider as NeoServiceProvider;

class NeoEloquentServiceProvider extends NeoServiceProvider {

	public function register()
	{
		$this->app['db']->extend('neo4j', function($config)
		{
			$conn = new Connection($config);
            $conn->setSchemaGrammar(new CypherGrammar);
            return $conn;
		});

		$this->app->booting(function(){
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('NeoEloquent', 'Vinelab\NeoEloquent\Eloquent\Model');
            $loader->alias('Neo4jSchema', 'Vinelab\NeoEloquent\Facade\Neo4jSchema');
		});


        $this->registerComponents();
	}
}
