<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OAuthSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UsersSeeder::class);

        $this->call(SettingsSeeder::class);
        $this->call(UserCaloriesSettingsSeeder::class);

        $this->call(MealsSeeder::class);

    }
}
