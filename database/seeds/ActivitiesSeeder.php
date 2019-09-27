<?php

use Illuminate\Database\Seeder;

class ActivitiesSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Models\Activity::class, 100)->create(['user_id' => UsersSeeder::$regularUser->id]);
    }
}
