<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $this->call(OAuthSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UsersSeeder::class);

        $this->call(SettingsSeeder::class);
        $this->call(UserActivitiesSettingsSeeder::class);

        $this->call(ActivitiesSeeder::class);
    }
}
