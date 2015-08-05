<?php

use Illuminate\Database\Seeder;
use App\Models\Neo\User;

class DatabaseSeeder extends Seeder
{
    protected $nodes_count = 40;

    public function run()
    {
        $faker = \Faker\Factory::create();
        $ids = [];

        for ($i = 0; $i < $this->nodes_count; $i++) {

            $user = User::create([
                'name' => $faker->name,
            ]);

            $range = array_slice($ids, max(0, $i - 10));
            shuffle($range);

            $user->friends()->attach(array_slice($range, 0, rand(0, 5)));
            $ids[] = $user->getKey();
        }
    }
}
