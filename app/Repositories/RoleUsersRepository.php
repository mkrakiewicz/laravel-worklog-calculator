<?php

namespace App\Repositories;

use App\Models\RoleUsers;
use InfyOm\Generator\Common\BaseRepository;

class RoleUsersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'role_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RoleUsers::class;
    }
}
