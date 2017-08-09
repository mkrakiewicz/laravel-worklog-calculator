<?php
use Illuminate\Database\Seeder;

class UserCaloriesSettingsSeeder extends Seeder
{

    public function run()
    {
        \App\Models\UsersCaloriesSettings::create(
            [
                'value' => 2500,
                'user_id' => UsersSeeder::$regularUser->id,
                'setting_id' => SettingsSeeder::$caloriesSetting->id
            ]
        );
    }
}
