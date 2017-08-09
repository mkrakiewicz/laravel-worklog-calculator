<?php

namespace Tests\Unit\Api;


use App\Http\Controllers\API\MealAPIController;
use App\Http\Requests\API\CreateMealAPIRequest;
use App\Http\Requests\API\UpdateMealAPIRequest;
use App\Models\Meal;
use App\Models\Role;
use App\Models\User;
use App\Repositories\MealRepository;
use Illuminate\Auth\AuthManager;
use Mockery;
use Tests\TestCase;

class MealApiTest extends TestCase
{
    private $currentUserId = 5;
    private $updatedMealId = 9;

    /**
     * @dataProvider createTestCases
     */
    public function testStore($hasAllCrudAccess, $requestUserId, $expectedUserId, $changedUserId)
    {
        $controller = new MealAPIController(
            $this->getCreateMealRepositoryMock(['user_id' => $expectedUserId]),
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
        $controller = new MealAPIController(
            $this->getUpdateMealRepositoryMock(['user_id' => $expectedUserId],$findWithoutFailResult),
            $this->getAuthManagerMock($hasAllCrudAccess)
        );
        $createRequest = $this->getUpdateRequestMock(['user_id' => $expectedUserId], $requestUserId, $expectedUserId,
            $changedUserId);
        $controller->update($this->updatedMealId, $createRequest);
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

    private function getCreateMealRepositoryMock($expectedCreateArray)
    {
        $mock = Mockery::mock(MealRepository::class);
        $mock->shouldReceive('create')->with($expectedCreateArray)->andReturn($this->getMealMock());
        return $mock;
    }

    private function getUpdateMealRepositoryMock($expectedUpdateArray,$findWithoutFailResult=false)
    {
        $mock = Mockery::mock(MealRepository::class);
        $mock->shouldReceive('findWithoutFail')->andReturn($findWithoutFailResult);
        if ($expectedUpdateArray)
        {
            $mock->shouldReceive('update')->with($expectedUpdateArray,$this->updatedMealId)->andReturn($this->getMealMock());
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
        $mock = Mockery::mock(CreateMealAPIRequest::class);
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
        $mock = Mockery::mock(UpdateMealAPIRequest::class);
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
        $mock->shouldReceive('hasAccess')->with([Role::CRUD_ALL_MEALS])->andReturn($hasAllCrudAccess);
        return $mock;
    }

    private function getMealMock()
    {
        $mock = Mockery::mock(Meal::class);
        $mock->shouldReceive('toArray')->andReturn([]);
        return $mock;
    }


}