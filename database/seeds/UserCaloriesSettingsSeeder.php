<?php
use Illuminate\Database\Seeder;

class UserWorklogsSettingsSeeder extends Seeder
{

    public function run()
    {
        \App\Models\UsersWorklogsSettings::create(
            [
                'value' => 2500,
                'user_id' => UsersSeeder::$regularUser->id,
                'setting_id' => SettingsSeeder::$worklogsSetting->id
            ]
        );
    }
}
