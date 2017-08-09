<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Common\BaseRepository;

class SettingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        "key",
        "value"
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Setting::class;
    }
}
