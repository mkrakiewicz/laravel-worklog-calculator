<?php

namespace Tests\Integration\Api;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeUsersCaloriesSettingsTrait;

class UsersCaloriesSettingsApiTest extends TestCase
{
    use MakeUsersCaloriesSettingsTrait, ApiTestTrait, WithoutMiddleware, DatabaseMigrations;

    protected function setUp()
    {
        $this->markTestIncomplete();
    }
    /**
     * @test
     */
    public function testCreateUsersCaloriesSettings()
    {
        $usersCaloriesSettings = $this->fakeUsersCaloriesSettingsData();
        $this->json('POST', '/api/usersCaloriesSettings', $usersCaloriesSettings);

        $this->assertApiResponse($usersCaloriesSettings);
    }

    /**
     * @test
     */
    public function testReadUsersCaloriesSettings()
    {
        $usersCaloriesSettings = $this->makeUsersCaloriesSettings();
        $this->json('GET', '/api/usersCaloriesSettings/'.$usersCaloriesSettings->id);

        $this->assertApiResponse($usersCaloriesSettings->toArray());
    }

    /**
     * @test
     */
    public function testUpdateUsersCaloriesSettings()
    {
        $usersCaloriesSettings = $this->makeUsersCaloriesSettings();
        $editedUsersCaloriesSettings = $this->fakeUsersCaloriesSettingsData();

        $this->json('PUT', '/api/usersCaloriesSettings/'.$usersCaloriesSettings->id, $editedUsersCaloriesSettings);

        $this->assertApiResponse($editedUsersCaloriesSettings);
    }

    /**
     * @test
     */
    public function testDeleteUsersCaloriesSettings()
    {
        $usersCaloriesSettings = $this->makeUsersCaloriesSettings();
        $this->json('DELETE', '/api/usersCaloriesSettings/'.$usersCaloriesSettings->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/usersCaloriesSettings/'.$usersCaloriesSettings->id);

        $this->assertResponseStatus(404);
    }
}
