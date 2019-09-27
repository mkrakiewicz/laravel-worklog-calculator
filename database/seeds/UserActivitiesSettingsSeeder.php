<?php
use Illuminate\Database\Seeder;

class UserActivitiesSettingsSeeder extends Seeder
{

    public function run()
    {
        \App\Models\UsersActivitiesSettings::create(
            [
                'value' => 2500,
                'user_id' => UsersSeeder::$regularUser->id,
                'setting_id' => SettingsSeeder::$worklogsSetting->id
            ]
        );
    }
}
