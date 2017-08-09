<?php
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{

    public static $caloriesSetting;

    public function run()
    {
        static::$caloriesSetting = \App\Models\Setting::create(
            [
                'key' => 'caloriesPerDay'
            ]
        );
    }
}
