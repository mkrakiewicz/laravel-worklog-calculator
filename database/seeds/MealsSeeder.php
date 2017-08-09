<?php
use Illuminate\Database\Seeder;

class MealsSeeder extends Seeder
{

    public static $mealList = [
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
        $this->createRandomMeals();
    }

    private function createRandomMeals()
    {
        factory(\App\Models\Meal::class, 100)->create(['user_id'=>UsersSeeder::$regularUser->id]);
    }
}
