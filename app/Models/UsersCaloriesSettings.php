<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UsersCaloriesSettings
 *
 * @package App\Models
 * @version July 20, 2017, 8:54 pm UTC
 * @property int $id
 * @property int $user_id
 * @property int $setting_id
 * @property int $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCaloriesSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCaloriesSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCaloriesSettings whereSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCaloriesSettings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCaloriesSettings whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCaloriesSettings whereValue($value)
 * @mixin \Eloquent
 */
class UsersCaloriesSettings extends Model
{
    public $table = 'users_calories_settings';

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
