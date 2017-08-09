<?php
use App\Models\Role;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public static $regularUser;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createRegularUser();
        $this->createUserManager();
        $this->createAdmin();

        $this->createRandomUsers();
    }

    private function createRegularUser()
    {
        static::$regularUser = \App\Models\User::create([
            'email' => 'user@domain.com',
            'name' => 'Test User',
            'password' => '123456'
        ]);
        $roles = static::$regularUser->roles();
        $roles->attach(RolesSeeder::$userRole);
    }

    private function createUserManager()
    {
        $roles = \App\Models\User::create([
            'email' => 'user_manager@domain.com',
            'name' => 'Test User Manager',
            'password' => '123456'
        ])->roles();
        $roles->attach(RolesSeeder::$userManagerRole);
    }

    private function createAdmin()
    {
        $roles = \App\Models\User::create([
            'email' => 'admin@domain.com',
            'name' => 'Test Admin',
            'password' => '123456'
        ])->roles();
        $roles->attach(RolesSeeder::$userRole);
        $roles->attach(RolesSeeder::$userManagerRole);
        $roles->attach(RolesSeeder::$adminRole);
    }

    private function createRandomUsers()
    {
        factory(\App\Models\User::class, 25)->create()->each(function ($u) {
            /** @var \App\Models\User $u */
            $u->roles()->attach(RolesSeeder::$userRole);
        });

        factory(\App\Models\User::class, 10)->create()->each(function ($u) {
            /** @var \App\Models\User $u */
            $u->roles()->attach(RolesSeeder::$adminRole);
        });

        factory(\App\Models\User::class, 10)->create()->each(function ($u) {
            /** @var \App\Models\User $u */
            $u->roles()->attach(RolesSeeder::$userManagerRole);
        });
    }
}
