<?php

namespace Tests\Integration\Api;


use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeMealTrait;
use Tests\Traits\MakeUserTrait;

class MealApiTest extends TestCase
{
    use MakeMealTrait, MakeUserTrait, ApiTestTrait, WithoutMiddleware, DatabaseMigrations;

    private $userRepo;
    private $user;

    protected function setUp()
    {
        $this->markTestIncomplete();
        parent::setUp();
        $this->userRepo = App::make(App\Repositories\UserRepository::class);
        $this->user = $this->userRepo->create($this->fakeUserData());
    }


    /**
     * @test
     */
    public function testCreateMeal()
    {
        $meal = $this->fakeMealData(['user_id' => $this->user->id]);
        $this->json('POST', '/api/meals', $meal);

        $this->assertApiResponse($meal);
    }

    /**
     * @test
     */
    public function testReadMeal()
    {
        $meal = $this->makeMeal(['user_id' => $this->user->id]);
        $this->json('GET', '/api/meals/' . $meal->id);

        $this->assertApiResponse($meal->toArray());
    }

    /**
     * @test
     */
    public function testUpdateMeal()
    {
        $meal = $this->makeMeal(['user_id' => $this->user->id]);
        $editedMeal = $this->fakeMealData(['user_id' => $this->user->id]);

        $this->json('PUT', '/api/meals/' . $meal->id, $editedMeal);

        $this->assertApiResponse($editedMeal);
    }

    /**
     * @test
     */
    public function testDeleteMeal()
    {
        $meal = $this->makeMeal(['user_id' => $this->user->id]);
        $this->json('DELETE', '/api/meals/' . $meal->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/meals/' . $meal->id);

        $this->assertResponseStatus(404);
    }
}
