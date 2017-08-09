<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class worklog
 *
 * @package App\Models
 * @version July 19, 2017, 7:18 pm UTC
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $worklogs
 * @property \Carbon\Carbon $time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\worklog onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\worklog whereWorklogs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\worklog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\worklog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\worklog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\worklog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\worklog whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\worklog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\worklog whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\worklog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\worklog withoutTrashed()
 * @mixin \Eloquent
 */
class worklog extends Model
{
    use SoftDeletes;

    public $table = 'worklogs';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'worklogs',
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
        'worklogs' => 'integer',
        'user_id' => 'integer',

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:255',
        'worklogs' => 'required|numeric',
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
