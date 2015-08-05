<?php

namespace Testing;

use Auth;
use Faker\Factory as FakerFactory;
use Illuminate\Foundation\Testing\TestCase as IlluminateTestCase;
use Illuminate\Contracts\Console\Kernel;

class TestCase extends IlluminateTestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    protected $faker;

    public function setUp()
    {
        parent::setUp();

        $this->faker = FakerFactory::create();
    }

    protected function createUser($save = false)
    {
        $userClass = config('auth.model');
        $user = new $userClass(['name' => $this->faker->name]);
        if ($save)
        {
            $user->save();
        }
        return $user;
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
