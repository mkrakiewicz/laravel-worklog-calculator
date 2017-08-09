<?php

namespace Tests\Integration\Repositories;


use App;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeUserTrait;

class UserRepositoryTest extends TestCase
{
    use MakeUserTrait, DatabaseMigrations;

    /**
     * @var UserRepository
     */
    protected $userRepo;

    public function setUp()
    {
        parent::setUp();
        $this->userRepo = App::make(UserRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateUser()
    {
        $user = $this->fakeUserData();
        /** @var User $createdUser */
        $createdUser = $this->userRepo->create($user);
        $arrayUser = collect($createdUser->toArray())->except([
            'id',
            'created_at',
            'updated_at',
            'deleted_at'
        ])->toArray();
        $this->assertTrue(Hash::check($user['password'], $createdUser->password));
        $this->assertNotNull($createdUser->id, 'Created User must have id specified');
        $this->assertNotNull(User::find($createdUser->id), 'User with given id must be in DB');
        $this->assertModelData($arrayUser, $user);
    }

    /**
     * @test read
     */
    public function testReadUser()
    {
        $user = $this->makeUser();
        $dbUser = $this->userRepo->find($user->id);
        $dbUser = $dbUser->toArray();
        $this->assertModelData($user->toArray(), $dbUser);
    }

    /**
     * @test update
     */
    public function testUpdateUser()
    {
        $user = $this->makeUser();
        $fakeUser = $this->fakeUserData();
        $updatedUser = $this->userRepo->update($fakeUser, $user->id);
        $arrayUser = collect($updatedUser->toArray())->except([
            'id',
            'created_at',
            'updated_at',
            'deleted_at'
        ])->toArray();
        $this->assertTrue(Hash::check($fakeUser['password'], $updatedUser->password));
        $this->assertModelData($arrayUser, $fakeUser);
        $dbUser = $this->userRepo->find($user->id);
        $dbUserArray = collect($dbUser->toArray())->except([
            'id',
            'created_at',
            'updated_at',
            'deleted_at'
        ])->toArray();
        $this->assertModelData($dbUserArray, $fakeUser);
    }

    /**
     * @test delete
     */
    public function testDeleteUser()
    {
        $user = $this->makeUser();
        $resp = $this->userRepo->delete($user->id);
        $this->assertTrue($resp);
        $this->assertNull(User::find($user->id), 'User should not exist in DB');
    }
}
