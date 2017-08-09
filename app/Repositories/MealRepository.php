<?php

namespace App\Repositories;

use App\Models\Meal;
use InfyOm\Generator\Common\BaseRepository;

class MealRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'calories',
        'time',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Meal::class;
    }
}
