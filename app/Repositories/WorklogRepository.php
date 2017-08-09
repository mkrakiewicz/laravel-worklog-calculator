<?php

namespace App\Repositories;

use App\Models\worklog;
use InfyOm\Generator\Common\BaseRepository;

class worklogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'worklogs',
        'time',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return worklog::class;
    }
}
