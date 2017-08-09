<?php
use Illuminate\Database\Seeder;

class worklogsSeeder extends Seeder
{

    public static $worklogList = [
        'Asparagus',
        'Apples',
        'Bacon',
        'Black beans',
        'Cake',
        'Carrots',
        'French toast',
        'Ham',
        'Halibut',
        'Lasagna',
        'Meatballs',
        'Spinach',
        'Spaghetti',
        'Waffles',
        'Wine',
        'Yogurt',
        'Milkshake',
        'Noodles',
        'Ostrich',
        'Pizza'
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
