<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersActivitiesSettings extends Model
{
    public $table = 'users_activities_settings';

    protected $dates = [];


    public $fillable = [
        'user_id',
        'setting_id',
        'value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'setting_id' => 'integer',
        'value' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|exists:users,id',
        'setting_id' => 'required|exists:settings,id',
        'value' => 'required|integer'
    ];
}
