<?php
use Illuminate\Database\Seeder;

class worklogsSeeder extends Seeder
{

    public static $worklogList = [
        'Writing',
        'Reading',
        'Calling',
        'Conference Call',
        'Lunch break',
        'Programming in PHP',
        'Programming in C#',
        'Estimating project',
        'Consultations',
        'Audit',
        'Testing',
    ];

    public function run()
    {
        $this->createRandomworklogs();
    }

    private function createRandomworklogs()
    {
        factory(\App\Models\worklog::class, 100)->create(['user_id'=>UsersSeeder::$regularUser->id]);
    }
}
