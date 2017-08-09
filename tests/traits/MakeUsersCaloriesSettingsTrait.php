<?php

namespace Tests\Traits;


use App;
use Faker\Factory as Faker;
use App\Models\UsersCaloriesSettings;
use App\Repositories\UsersCaloriesSettingsRepository;

trait MakeUsersCaloriesSettingsTrait
{
    /**
     * Create fake instance of UsersCaloriesSettings and save it in database
     *
     * @param array $usersCaloriesSettingsFields
     * @return UsersCaloriesSettings
     */
    public function makeUsersCaloriesSettings($usersCaloriesSettingsFields = [])
    {
        /** @var UsersCaloriesSettingsRepository $usersCaloriesSettingsRepo */
        $usersCaloriesSettingsRepo = App::make(UsersCaloriesSettingsRepository::class);
        $theme = $this->fakeUsersCaloriesSettingsData($usersCaloriesSettingsFields);
        return $usersCaloriesSettingsRepo->create($theme);
    }

    /**
     * Get fake instance of UsersCaloriesSettings
     *
     * @param array $usersCaloriesSettingsFields
     * @return UsersCaloriesSettings
     */
    public function fakeUsersCaloriesSettings($usersCaloriesSettingsFields = [])
    {
        return new UsersCaloriesSettings($this->fakeUsersCaloriesSettingsData($usersCaloriesSettingsFields));
    }

    /**
     * Get fake data of UsersCaloriesSettings
     *
     * @param array $postFields
     * @return array
     */
    public function fakeUsersCaloriesSettingsData($usersCaloriesSettingsFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'setting_id' => $fake->randomDigitNotNull,
            'value' => $fake->randomDigitNotNull
        ], $usersCaloriesSettingsFields);
    }
}
