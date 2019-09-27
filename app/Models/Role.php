<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    const ACCESS_ACTIVITIES = 'access-activities';
    const CRUD_OWN_ACTIVITIES = 'crud-own-activities';
    const CRUD_ALL_ACTIVITIES = 'crud-all-activities';


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
