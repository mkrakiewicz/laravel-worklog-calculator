<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const MINUTES_PER_DAY_SETTING = 'minutesPerDay';

    public $fillable = [
        'key',
        'value'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'key' => 'required'
    ];
}
