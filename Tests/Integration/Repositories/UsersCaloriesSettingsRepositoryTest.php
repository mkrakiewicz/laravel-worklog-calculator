<?php

namespace Tests\Integration\Repositories;


use App;
use App\Models\UsersCaloriesSettings;
use App\Repositories\UsersCaloriesSettingsRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeSettingTrait;
use Tests\Traits\MakeUsersCaloriesSettingsTrait;
use Tests\Traits\MakeUserTrait;

class UsersCaloriesSettingsRepositoryTest extends TestCase
{
    use MakeUsersCaloriesSettingsTrait, MakeSettingTrait, MakeUserTrait, DatabaseMigrations;

    /**
     * @var UsersCaloriesSettingsRepository
     */
    protected $usersCaloriesSettingsRepo;
    private $settingRepo;
    private $userRepo;
    private $setting;
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->usersCaloriesSettingsRepo = App::make(UsersCaloriesSettingsRepository::class);
        /** @var App\Repositories\SettingRepository */
        $this->settingRepo = App::make(App\Repositories\SettingRepository::class);
        $this->setting = $this->settingRepo->create($this->fakeSettingData());

        /** @var App\Repositories\UserRepository userRepo */
        $this->userRepo = App::make(App\Repositories\UserRepository::class);
        $this->user = $this->userRepo->create($this->fakeUserData());

    }

    /**
     * @test create
     */
    public function testCreateUsersCaloriesSettings()
    {
        $usersCaloriesSettings = $this->fakeUsersCaloriesSettingsData([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $createdUsersCaloriesSettings = $this->usersCaloriesSettingsRepo->create($usersCaloriesSettings);
        $createdUsersCaloriesSettings = $createdUsersCaloriesSettings->toArray();
        $this->assertArrayHasKey('id', $createdUsersCaloriesSettings);
        $this->assertNotNull($createdUsersCaloriesSettings['id'],
            'Created UsersCaloriesSettings must have id specified');
        $this->assertNotNull(UsersCaloriesSettings::find($createdUsersCaloriesSettings['id']),
            'UsersCaloriesSettings with given id must be in DB');
        $this->assertModelData($usersCaloriesSettings, $createdUsersCaloriesSettings);
    }

    /**
     * @test read
     */
    public function testReadUsersCaloriesSettings()
    {
        $usersCaloriesSettings = $this->makeUsersCaloriesSettings([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $dbUsersCaloriesSettings = $this->usersCaloriesSettingsRepo->find($usersCaloriesSettings->id);
        $dbUsersCaloriesSettings = $dbUsersCaloriesSettings->toArray();
        $this->assertModelData($usersCaloriesSettings->toArray(), $dbUsersCaloriesSettings);
    }

    /**
     * @test update
     */
    public function testUpdateUsersCaloriesSettings()
    {
        $usersCaloriesSettings = $this->makeUsersCaloriesSettings([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $fakeUsersCaloriesSettings = $this->fakeUsersCaloriesSettingsData([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $updatedUsersCaloriesSettings = $this->usersCaloriesSettingsRepo->update($fakeUsersCaloriesSettings,
            $usersCaloriesSettings->id);
        $this->assertModelData($fakeUsersCaloriesSettings, $updatedUsersCaloriesSettings->toArray());
        $dbUsersCaloriesSettings = $this->usersCaloriesSettingsRepo->find($usersCaloriesSettings->id);
        $this->assertModelData($fakeUsersCaloriesSettings, $dbUsersCaloriesSettings->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteUsersCaloriesSettings()
    {
        $usersCaloriesSettings = $this->makeUsersCaloriesSettings([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $resp = $this->usersCaloriesSettingsRepo->delete($usersCaloriesSettings->id);
        $this->assertTrue($resp);
        $this->assertNull(UsersCaloriesSettings::find($usersCaloriesSettings->id),
            'UsersCaloriesSettings should not exist in DB');
    }
}
