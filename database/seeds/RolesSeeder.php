<?php
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public static $userRole, $userManagerRole, $adminRole;

    public function run()
    {
        static::$userRole = Role::create(
            [
                'name' => 'User',
                'slug' => 'user',
                'permissions' => [
                    Role::ACCESS_MEALS => true,
                    Role::CRUD_OWN_MEALS => true,
                    Role::ACCESS_USER_SETTINGS => true,
                    Role::CRUD_OWN_USER_SETTINGS => true
                ]
            ]
        );

        static::$userManagerRole = Role::create(
            [
                'name' => 'User Manager',
                'slug' => 'user-manager',
                'permissions' => [
                    Role::ACCESS_USERS => true,
                    Role::CRUD_ALL_USERS => true,
                    Role::ACCESS_ROLE_USERS => true,
                    Role::CRUD_ROLE_USERS => true,
                ]
            ]
        );

        static::$adminRole = Role::create(
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'permissions' => [
                    Role::CRUD_ALL_MEALS => true,
                    Role::CRUD_ALL_USER_SETTINGS => true,
                ]
            ]
        );
    }
}
