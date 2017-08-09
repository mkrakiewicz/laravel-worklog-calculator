<?php

namespace Tests\Integration\Api;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRoleUsersTrait;

class RoleUsersApiTest extends TestCase
{
    use MakeRoleUsersTrait, ApiTestTrait, WithoutMiddleware, DatabaseMigrations;

    protected function setUp()
    {
        $this->markTestIncomplete();
    }
    /**
     * @test
     */
    public function testCreateRoleUsers()
    {
        $roleUsers = $this->fakeRoleUsersData();
        $this->json('POST', '/api/roleUsers', $roleUsers);

        $this->assertApiResponse($roleUsers);
    }

    /**
     * @test
     */
    public function testReadRoleUsers()
    {
        $roleUsers = $this->makeRoleUsers();
        $this->json('GET', '/api/roleUsers/'.$roleUsers->id);

        $this->assertApiResponse($roleUsers->toArray());
    }

    /**
     * @test
     */
    public function testUpdateRoleUsers()
    {
        $roleUsers = $this->makeRoleUsers();
        $editedRoleUsers = $this->fakeRoleUsersData();

        $this->json('PUT', '/api/roleUsers/'.$roleUsers->id, $editedRoleUsers);

        $this->assertApiResponse($editedRoleUsers);
    }

    /**
     * @test
     */
    public function testDeleteRoleUsers()
    {
        $roleUsers = $this->makeRoleUsers();
        $this->json('DELETE', '/api/roleUsers/'.$roleUsers->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/roleUsers/'.$roleUsers->id);

        $this->assertResponseStatus(404);
    }
}
