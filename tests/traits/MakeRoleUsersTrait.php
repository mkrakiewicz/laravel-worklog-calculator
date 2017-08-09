<?php

namespace Tests\Traits; 


use App;
use Faker\Factory as Faker;
use App\Models\RoleUsers;
use App\Repositories\RoleUsersRepository;

trait MakeRoleUsersTrait
{
    /**
     * Create fake instance of RoleUsers and save it in database
     *
     * @param array $roleUsersFields
     * @return RoleUsers
     */
    public function makeRoleUsers($roleUsersFields = [])
    {
        /** @var RoleUsersRepository $roleUsersRepo */
        $roleUsersRepo = App::make(RoleUsersRepository::class);
        $theme = $this->fakeRoleUsersData($roleUsersFields);
        return $roleUsersRepo->create($theme);
    }

    /**
     * Get fake instance of RoleUsers
     *
     * @param array $roleUsersFields
     * @return RoleUsers
     */
    public function fakeRoleUsers($roleUsersFields = [])
    {
        return new RoleUsers($this->fakeRoleUsersData($roleUsersFields));
    }

    /**
     * Get fake data of RoleUsers
     *
     * @param array $postFields
     * @return array
     */
    public function fakeRoleUsersData($roleUsersFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'role_id' => $fake->randomDigitNotNull,
        ], $roleUsersFields);
    }
}
