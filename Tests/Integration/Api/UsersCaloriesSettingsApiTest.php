<?php

namespace Tests\Integration\Api;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeUsersWorklogsSettingsTrait;

class UsersWorklogsSettingsApiTest extends TestCase
{
    use MakeUsersWorklogsSettingsTrait, ApiTestTrait, WithoutMiddleware, DatabaseMigrations;

    protected function setUp()
    {
        $this->markTestIncomplete();
    }
    /**
     * @test
     */
    public function testCreateUsersWorklogsSettings()
    {
        $usersWorklogsSettings = $this->fakeUsersWorklogsSettingsData();
        $this->json('POST', '/api/usersWorklogsSettings', $usersWorklogsSettings);

        $this->assertApiResponse($usersWorklogsSettings);
    }

    /**
     * @test
     */
    public function testReadUsersWorklogsSettings()
    {
        $usersWorklogsSettings = $this->makeUsersWorklogsSettings();
        $this->json('GET', '/api/usersWorklogsSettings/'.$usersWorklogsSettings->id);

        $this->assertApiResponse($usersWorklogsSettings->toArray());
    }

    /**
     * @test
     */
    public function testUpdateUsersWorklogsSettings()
    {
        $usersWorklogsSettings = $this->makeUsersWorklogsSettings();
        $editedUsersWorklogsSettings = $this->fakeUsersWorklogsSettingsData();

        $this->json('PUT', '/api/usersWorklogsSettings/'.$usersWorklogsSettings->id, $editedUsersWorklogsSettings);

        $this->assertApiResponse($editedUsersWorklogsSettings);
    }

    /**
     * @test
     */
    public function testDeleteUsersWorklogsSettings()
    {
        $usersWorklogsSettings = $this->makeUsersWorklogsSettings();
        $this->json('DELETE', '/api/usersWorklogsSettings/'.$usersWorklogsSettings->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/usersWorklogsSettings/'.$usersWorklogsSettings->id);

        $this->assertResponseStatus(404);
    }
}
