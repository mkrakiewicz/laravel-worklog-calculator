<?php

namespace Tests\Integration\Repositories;


use App;
use App\Models\Meal;
use App\Repositories\MealRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeMealTrait;
use Tests\Traits\MakeUserTrait;

class MealRepositoryTest extends TestCase
{
    private $user;
    use MakeMealTrait, MakeUserTrait, DatabaseMigrations;

    /**
     * @var MealRepository
     */
    protected $mealRepo;

    /**
     * @var App\Repositories\UserRepository
     */
    private $userRepo;

    public function setUp()
    {
        parent::setUp();
        $this->mealRepo = App::make(MealRepository::class);
        $this->userRepo = App::make(App\Repositories\UserRepository::class);
        $this->user = $this->userRepo->create($this->fakeUserData());
    }

    /**
     * @test create
     */
    public function testCreateMeal()
    {
        $meal = $this->fakeMealData(['user_id' => $this->user->id]);
        $createdMeal = $this->mealRepo->create($meal);
        $createdMeal = $createdMeal->toArray();
        $this->assertArrayHasKey('id', $createdMeal);
        $this->assertNotNull($createdMeal['id'], 'Created Meal must have id specified');
        $this->assertNotNull(Meal::find($createdMeal['id']), 'Meal with given id must be in DB');
        $this->assertModelData($meal, $createdMeal);
    }

    /**
     * @test read
     */
    public function testReadMeal()
    {
        $meal = $this->makeMeal(['user_id' => $this->user->id]);
        $dbMeal = $this->mealRepo->find($meal->id);
        $dbMeal = $dbMeal->toArray();
        $this->assertModelData($meal->toArray(), $dbMeal);
    }

    /**
     * @test update
     */
    public function testUpdateMeal()
    {
        $meal = $this->makeMeal(['user_id' => $this->user->id]);
        $fakeMeal = $this->fakeMealData(['user_id' => $this->user->id]);
        $updatedMeal = $this->mealRepo->update($fakeMeal, $meal->id);
        $this->assertModelData($fakeMeal, $updatedMeal->toArray());
        $dbMeal = $this->mealRepo->find($meal->id);
        $this->assertModelData($fakeMeal, $dbMeal->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMeal()
    {
        $meal = $this->makeMeal(['user_id' => $this->user->id]);
        $resp = $this->mealRepo->delete($meal->id);
        $this->assertTrue($resp);
        $this->assertNull(Meal::find($meal->id), 'Meal should not exist in DB');
    }
}
