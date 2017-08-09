<?php

namespace App\Repositories;

use App\Models\User;
use InfyOm\Generator\Common\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }


    /**
     * @param User $model
     * @param $attributes
     * @return mixed
     */
    public function updateRelations($model, $attributes)
    {
//        if (isset($model->role_id))
//            $model->roles()->attach(['role_id' => 1]);
        return parent::updateRelations($model, $attributes);
    }
}
