<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 *
 * @package App\Models
 * @version July 21, 2017, 2:46 pm UTC
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property array $permissions
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    const ACCESS_worklogS = 'access-worklogs';
    const CRUD_OWN_worklogS = 'crud-own-worklogs';
    const CRUD_ALL_worklogS = 'crud-all-worklogs';


    const ACCESS_USER_SETTINGS = 'access-user-settings';
    const CRUD_OWN_USER_SETTINGS = 'crud-own-user-settings';
    const CRUD_ALL_USER_SETTINGS = 'crud-user-settings';


    const ACCESS_USERS = 'access-users';
    const CRUD_ALL_USERS = 'crud-users';

    const ACCESS_ROLE_USERS = 'access-role-users';
    const CRUD_ROLE_USERS = 'crud-role-users';

    public $table = 'roles';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


//    protected $dates = [];


    public $fillable = [
        'name',
        'slug',
        'permissions'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'permissions' => 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'string',
        'slug' => 'string',
        'permissions' => 'string'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'role_users');
    }

    public function hasAccess(array $permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    private function hasPermission($permission)
    {
        return (isset($this->permissions[$permission]) && $this->permissions[$permission]) ? true : false;
    }
}
