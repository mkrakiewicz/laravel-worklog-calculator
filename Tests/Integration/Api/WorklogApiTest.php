<?php

namespace Tests\Integration\Api;


use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeworklogTrait;
use Tests\Traits\MakeUserTrait;

class worklogApiTest extends TestCase
{
    use MakeworklogTrait, MakeUserTrait, ApiTestTrait, WithoutMiddleware, DatabaseMigrations;

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
    public function testCreateworklog()
    {
        $worklog = $this->fakeworklogData(['user_id' => $this->user->id]);
        $this->json('POST', '/api/worklogs', $worklog);

        $this->assertApiResponse($worklog);
    }

    /**
     * @test
     */
    public function testReadworklog()
    {
        $worklog = $this->makeworklog(['user_id' => $this->user->id]);
        $this->json('GET', '/api/worklogs/' . $worklog->id);

        $this->assertApiResponse($worklog->toArray());
    }

    /**
     * @test
     */
    public function testUpdateworklog()
    {
        $worklog = $this->makeworklog(['user_id' => $this->user->id]);
        $editedworklog = $this->fakeworklogData(['user_id' => $this->user->id]);

        $this->json('PUT', '/api/worklogs/' . $worklog->id, $editedworklog);

        $this->assertApiResponse($editedworklog);
    }

    /**
     * @test
     */
    public function testDeleteworklog()
    {
        $worklog = $this->makeworklog(['user_id' => $this->user->id]);
        $this->json('DELETE', '/api/worklogs/' . $worklog->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/worklogs/' . $worklog->id);

        $this->assertResponseStatus(404);
    }
}
