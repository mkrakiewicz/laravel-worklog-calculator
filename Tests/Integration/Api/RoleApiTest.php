<?php

namespace Tests\Integration\Api;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRoleTrait;

class RoleApiTest extends TestCase
{
    use MakeRoleTrait, ApiTestTrait, WithoutMiddleware, DatabaseMigrations;

    protected function setUp()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function testCreateRole()
    {
        $role = $this->fakeRoleData();
        $this->json('POST', '/api/roles', $role);

        $this->assertApiResponse($role);
    }

    /**
     * @test
     */
    public function testReadRole()
    {
        $role = $this->makeRole();
        $this->json('GET', '/api/roles/'.$role->id);

        $this->assertApiResponse($role->toArray());
    }

    /**
     * @test
     */
    public function testUpdateRole()
    {
        $role = $this->makeRole();
        $editedRole = $this->fakeRoleData();

        $this->json('PUT', '/api/roles/'.$role->id, $editedRole);

        $this->assertApiResponse($editedRole);
    }

    /**
     * @test
     */
    public function testDeleteRole()
    {
        $role = $this->makeRole();
        $this->json('DELETE', '/api/roles/'.$role->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/roles/'.$role->id);

        $this->assertResponseStatus(404);
    }
}
