<?php

namespace Tests\Traits;


use App;
use Faker\Factory as Faker;
use App\Models\UsersWorklogsSettings;
use App\Repositories\UsersWorklogsSettingsRepository;

trait MakeUsersWorklogsSettingsTrait
{
    /**
     * Create fake instance of UsersWorklogsSettings and save it in database
     *
     * @param array $usersWorklogsSettingsFields
     * @return UsersWorklogsSettings
     */
    public function makeUsersWorklogsSettings($usersWorklogsSettingsFields = [])
    {
        /** @var UsersWorklogsSettingsRepository $usersWorklogsSettingsRepo */
        $usersWorklogsSettingsRepo = App::make(UsersWorklogsSettingsRepository::class);
        $theme = $this->fakeUsersWorklogsSettingsData($usersWorklogsSettingsFields);
        return $usersWorklogsSettingsRepo->create($theme);
    }

    /**
     * Get fake instance of UsersWorklogsSettings
     *
     * @param array $usersWorklogsSettingsFields
     * @return UsersWorklogsSettings
     */
    public function fakeUsersWorklogsSettings($usersWorklogsSettingsFields = [])
    {
        return new UsersWorklogsSettings($this->fakeUsersWorklogsSettingsData($usersWorklogsSettingsFields));
    }

    /**
     * Get fake data of UsersWorklogsSettings
     *
     * @param array $postFields
     * @return array
     */
    public function fakeUsersWorklogsSettingsData($usersWorklogsSettingsFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'setting_id' => $fake->randomDigitNotNull,
            'value' => $fake->randomDigitNotNull
        ], $usersWorklogsSettingsFields);
    }
}
