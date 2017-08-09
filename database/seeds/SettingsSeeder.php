<?php
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{

    public static $worklogsSetting;

    public function run()
    {
        static::$worklogsSetting = \App\Models\Setting::create(
            [
                'key' => 'worklogsPerDay'
            ]
        );
    }
}
