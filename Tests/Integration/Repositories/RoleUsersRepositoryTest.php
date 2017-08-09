<?php

namespace Tests\Integration\Repositories;


use App;
use App\Models\RoleUsers;
use App\Repositories\RoleUsersRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeRoleTrait;
use Tests\Traits\MakeRoleUsersTrait;
use Tests\Traits\MakeUserTrait;

class RoleUsersRepositoryTest extends TestCase
{
    use MakeRoleUsersTrait, MakeUserTrait, MakeRoleTrait, DatabaseMigrations, DatabaseTransactions;

    /**
     * @var RoleUsersRepository
     */
    protected $roleUsersRepo;
    private $userRepo;
    private $user;
    private $roleRepo;
    private $role;

    public function setUp()
    {
        parent::setUp();
        $this->roleUsersRepo = App::make(RoleUsersRepository::class);

        /** @var App\Repositories\RoleRepository userRepo */
        $this->roleRepo = App::make(App\Repositories\RoleRepository::class);
        $this->role = $this->roleRepo->create($this->fakeRoleData());
        /** @var App\Repositories\UserRepository userRepo */
        $this->userRepo = App::make(App\Repositories\UserRepository::class);
        $this->user = $this->userRepo->create($this->fakeUserData(['role_id' => $this->role->id]));
    }


    /**
     * @test create
     */
    public function testCreateRoleUsers()
    {
        $roleUsers = $this->fakeRoleUsersData(['user_id' => $this->user->id, 'role_id' => $this->role->id]);
        $createdRoleUsers = $this->roleUsersRepo->create($roleUsers);
        $createdRoleUsers = $createdRoleUsers->toArray();
        $this->assertArrayHasKey('id', $createdRoleUsers);
        $this->assertNotNull($createdRoleUsers['id'], 'Created RoleUsers must have id specified');
        $this->assertNotNull(RoleUsers::find($createdRoleUsers['id']), 'RoleUsers with given id must be in DB');
        $this->assertModelData($roleUsers, $createdRoleUsers);
    }

    /**
     * @test read
     */
    public function testReadRoleUsers()
    {
        $roleUsers = $this->makeRoleUsers(['user_id' => $this->user->id, 'role_id' => $this->role->id]);
        $dbRoleUsers = $this->roleUsersRepo->find($roleUsers->id);
        $dbRoleUsers = $dbRoleUsers->toArray();
        $this->assertModelData($roleUsers->toArray(), $dbRoleUsers);
    }

    /**
     * @test update
     */
    public function testUpdateRoleUsers()
    {
        $roleUsers = $this->makeRoleUsers(['user_id' => $this->user->id, 'role_id' => $this->role->id]);
        $fakeRoleUsers = $this->fakeRoleUsersData(['user_id' => $this->user->id, 'role_id' => $this->role->id]);
        $updatedRoleUsers = $this->roleUsersRepo->update($fakeRoleUsers, $roleUsers->id);
        $this->assertModelData($fakeRoleUsers, $updatedRoleUsers->toArray());
        $dbRoleUsers = $this->roleUsersRepo->find($roleUsers->id);
        $this->assertModelData($fakeRoleUsers, $dbRoleUsers->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteRoleUsers()
    {
        $roleUsers = $this->makeRoleUsers(['user_id' => $this->user->id, 'role_id' => $this->role->id]);
        $resp = $this->roleUsersRepo->delete($roleUsers->id);
        $this->assertTrue($resp);
        $this->assertNull(RoleUsers::find($roleUsers->id), 'RoleUsers should not exist in DB');
    }
}
