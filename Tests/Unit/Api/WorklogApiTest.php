<?php

namespace Tests\Unit\Api;


use App\Http\Controllers\API\worklogAPIController;
use App\Http\Requests\API\CreateworklogAPIRequest;
use App\Http\Requests\API\UpdateworklogAPIRequest;
use App\Models\worklog;
use App\Models\Role;
use App\Models\User;
use App\Repositories\worklogRepository;
use Illuminate\Auth\AuthManager;
use Mockery;
use Tests\TestCase;

class worklogApiTest extends TestCase
{
    private $currentUserId = 5;
    private $updatedworklogId = 9;

    /**
     * @dataProvider createTestCases
     */
    public function testStore($hasAllCrudAccess, $requestUserId, $expectedUserId, $changedUserId)
    {
        $controller = new worklogAPIController(
            $this->getCreateworklogRepositoryMock(['user_id' => $expectedUserId]),
            $this->getAuthManagerMock($hasAllCrudAccess)
        );
        $createRequest = $this->getCreateRequestMock(['user_id' => $expectedUserId], $requestUserId, $expectedUserId,
            $changedUserId);
        $controller->store($createRequest);
    }

    /**
     * @dataProvider updateTestCases
     */
    public function testUpdate($hasAllCrudAccess, $requestUserId, $expectedUserId, $changedUserId,$findWithoutFailResult)
    {
        $controller = new worklogAPIController(
            $this->getUpdateworklogRepositoryMock(['user_id' => $expectedUserId],$findWithoutFailResult),
            $this->getAuthManagerMock($hasAllCrudAccess)
        );
        $createRequest = $this->getUpdateRequestMock(['user_id' => $expectedUserId], $requestUserId, $expectedUserId,
            $changedUserId);
        $controller->update($this->updatedworklogId, $createRequest);
    }

    public function createTestCases()
    {
        return [
            'normal user without user_id' => [
                'hasAllCrudAccess' => false,
                'requestUserId' => null,
                'expectedUserId' => $this->currentUserId,
                'changedUserId' => true
            ],
            'normal user with user_id' => [
                'hasAllCrudAccess' => false,
                'requestUserId' => 7,
                'expectedUserId' => $this->currentUserId,
                'changedUserId' => true
            ],
            'admin with user_id' => [
                'hasAllCrudAccess' => true,
                'requestUserId' => 7,
                'expectedUserId' => 7,
                'changedUserId' => false
            ],
            'admin without user_id' => [
                'hasAllCrudAccess' => true,
                'requestUserId' => null,
                'expectedUserId' => $this->currentUserId,
                'changedUserId' => true
            ]
        ];
    }


    public function updateTestCases()
    {
        return [
            'normal user without user_id' => [
                'hasAllCrudAccess' => false,
                'requestUserId' => null,
                'expectedUserId' => $this->currentUserId,
                'changedUserId' => true,
                'findWithoutFailResult'=>['something']
            ],
            'normal user with user_id' => [
                'hasAllCrudAccess' => false,
                'requestUserId' => 7,
                'expectedUserId' => $this->currentUserId,
                'changedUserId' => true,
                'findWithoutFailResult'=>['something']

            ],
            'admin with user_id' => [
                'hasAllCrudAccess' => true,
                'requestUserId' => 7,
                'expectedUserId' => 8,
                'changedUserId' => false,
                'findWithoutFailResult'=>['something']

            ],
            'admin without user_id' => [
                'hasAllCrudAccess' => true,
                'requestUserId' => null,
                'expectedUserId' => 11,
                'changedUserId' => false,
                'findWithoutFailResult'=>['something']

            ]
        ];
    }

    private function getCreateworklogRepositoryMock($expectedCreateArray)
    {
        $mock = Mockery::mock(worklogRepository::class);
        $mock->shouldReceive('create')->with($expectedCreateArray)->andReturn($this->getworklogMock());
        return $mock;
    }

    private function getUpdateworklogRepositoryMock($expectedUpdateArray,$findWithoutFailResult=false)
    {
        $mock = Mockery::mock(worklogRepository::class);
        $mock->shouldReceive('findWithoutFail')->andReturn($findWithoutFailResult);
        if ($expectedUpdateArray)
        {
            $mock->shouldReceive('update')->with($expectedUpdateArray,$this->updatedworklogId)->andReturn($this->getworklogMock());
        }
        return $mock;
    }

    private function getAuthManagerMock($hasAllCrudAccess)
    {
        $mock = Mockery::mock(AuthManager::class);
        $mock->shouldReceive('id')->andReturn($this->currentUserId);
        $mock->shouldReceive('user')->andReturn($this->getUserMock($hasAllCrudAccess));
        return $mock;
    }

    private function getCreateRequestMock($expectedAllArray, $requestUserId, $expectedUserId, $changedUserId)
    {
        $mock = Mockery::mock(CreateworklogAPIRequest::class);
        $mock->shouldReceive('offsetExists')->with('user_id')->andReturn(true);
        $mock->shouldReceive('offsetGet')->with('user_id')->andReturn($requestUserId);
        if ($changedUserId) {
            $mock->shouldReceive('offsetSet')->with('user_id', $expectedUserId);
        }
        $mock->shouldReceive('all')->andReturn($expectedAllArray);

        return $mock;
    }

    private function getUpdateRequestMock($expectedAllArray, $requestUserId, $expectedUserId, $changedUserId)
    {
        $mock = Mockery::mock(UpdateworklogAPIRequest::class);
        $mock->shouldReceive('offsetExists')->with('user_id')->andReturn(true);
        $mock->shouldReceive('offsetGet')->with('user_id')->andReturn($requestUserId);
        if ($changedUserId) {
            $mock->shouldReceive('offsetSet')->with('user_id', $expectedUserId);
        }
        $mock->shouldReceive('all')->andReturn($expectedAllArray);

        return $mock;
    }

    private function getUserMock($hasAllCrudAccess)
    {
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('hasAccess')->with([Role::CRUD_ALL_worklogS])->andReturn($hasAllCrudAccess);
        return $mock;
    }

    private function getworklogMock()
    {
        $mock = Mockery::mock(worklog::class);
        $mock->shouldReceive('toArray')->andReturn([]);
        return $mock;
    }


}