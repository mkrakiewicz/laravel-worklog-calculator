<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Meal
 *
 * @package App\Models
 * @version July 19, 2017, 7:18 pm UTC
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $calories
 * @property \Carbon\Carbon $time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Meal onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereCalories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Meal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Meal withoutTrashed()
 * @mixin \Eloquent
 */
class Meal extends Model
{
    use SoftDeletes;

    public $table = 'meals';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'calories',
        'time',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'time' => 'datetime',
        'calories' => 'integer',
        'user_id' => 'integer',

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:255',
        'calories' => 'required|numeric',
        'time' => 'required',
        'user_id' => 'exists:users,id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
