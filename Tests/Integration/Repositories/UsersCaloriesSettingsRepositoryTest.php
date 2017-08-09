<?php

namespace Tests\Integration\Repositories;


use App;
use App\Models\UsersWorklogsSettings;
use App\Repositories\UsersWorklogsSettingsRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeSettingTrait;
use Tests\Traits\MakeUsersWorklogsSettingsTrait;
use Tests\Traits\MakeUserTrait;

class UsersWorklogsSettingsRepositoryTest extends TestCase
{
    use MakeUsersWorklogsSettingsTrait, MakeSettingTrait, MakeUserTrait, DatabaseMigrations;

    /**
     * @var UsersWorklogsSettingsRepository
     */
    protected $usersWorklogsSettingsRepo;
    private $settingRepo;
    private $userRepo;
    private $setting;
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->usersWorklogsSettingsRepo = App::make(UsersWorklogsSettingsRepository::class);
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
    public function testCreateUsersWorklogsSettings()
    {
        $usersWorklogsSettings = $this->fakeUsersWorklogsSettingsData([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $createdUsersWorklogsSettings = $this->usersWorklogsSettingsRepo->create($usersWorklogsSettings);
        $createdUsersWorklogsSettings = $createdUsersWorklogsSettings->toArray();
        $this->assertArrayHasKey('id', $createdUsersWorklogsSettings);
        $this->assertNotNull($createdUsersWorklogsSettings['id'],
            'Created UsersWorklogsSettings must have id specified');
        $this->assertNotNull(UsersWorklogsSettings::find($createdUsersWorklogsSettings['id']),
            'UsersWorklogsSettings with given id must be in DB');
        $this->assertModelData($usersWorklogsSettings, $createdUsersWorklogsSettings);
    }

    /**
     * @test read
     */
    public function testReadUsersWorklogsSettings()
    {
        $usersWorklogsSettings = $this->makeUsersWorklogsSettings([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $dbUsersWorklogsSettings = $this->usersWorklogsSettingsRepo->find($usersWorklogsSettings->id);
        $dbUsersWorklogsSettings = $dbUsersWorklogsSettings->toArray();
        $this->assertModelData($usersWorklogsSettings->toArray(), $dbUsersWorklogsSettings);
    }

    /**
     * @test update
     */
    public function testUpdateUsersWorklogsSettings()
    {
        $usersWorklogsSettings = $this->makeUsersWorklogsSettings([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $fakeUsersWorklogsSettings = $this->fakeUsersWorklogsSettingsData([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $updatedUsersWorklogsSettings = $this->usersWorklogsSettingsRepo->update($fakeUsersWorklogsSettings,
            $usersWorklogsSettings->id);
        $this->assertModelData($fakeUsersWorklogsSettings, $updatedUsersWorklogsSettings->toArray());
        $dbUsersWorklogsSettings = $this->usersWorklogsSettingsRepo->find($usersWorklogsSettings->id);
        $this->assertModelData($fakeUsersWorklogsSettings, $dbUsersWorklogsSettings->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteUsersWorklogsSettings()
    {
        $usersWorklogsSettings = $this->makeUsersWorklogsSettings([
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id
        ]);
        $resp = $this->usersWorklogsSettingsRepo->delete($usersWorklogsSettings->id);
        $this->assertTrue($resp);
        $this->assertNull(UsersWorklogsSettings::find($usersWorklogsSettings->id),
            'UsersWorklogsSettings should not exist in DB');
    }
}
