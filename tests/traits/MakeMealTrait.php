<?php

namespace Tests\Traits; 

use App;
use Faker\Factory as Faker;
use App\Models\Meal;
use App\Repositories\MealRepository;

trait MakeMealTrait
{
    /**
     * Create fake instance of Meal and save it in database
     *
     * @param array $mealFields
     * @return Meal
     */
    public function makeMeal($mealFields = [])
    {
        /** @var MealRepository $mealRepo */
        $mealRepo = App::make(MealRepository::class);
        $theme = $this->fakeMealData($mealFields);
        return $mealRepo->create($theme);
    }

    /**
     * Get fake instance of Meal
     *
     * @param array $mealFields
     * @return Meal
     */
    public function fakeMeal($mealFields = [])
    {
        return new Meal($this->fakeMealData($mealFields));
    }

    /**
     * Get fake data of Meal
     *
     * @param array $postFields
     * @return array
     */
    public function fakeMealData($mealFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'calories' => $fake->numberBetween(50,450),
            'time' => $fake->time('Y-m-d H:i:s'),
            'user_id' => $fake->numberBetween(1,44)
        ], $mealFields);
    }
}
