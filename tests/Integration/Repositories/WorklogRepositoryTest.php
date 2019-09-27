<?php

namespace tests\Integration\Repositories;


use App;
use App\Models\Activity;
use App\Repositories\worklogRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\ApiTestTrait;
use tests\TestCase;
use tests\Traits\MakeworklogTrait;
use tests\Traits\MakeUserTrait;

class worklogRepositoryTest extends TestCase
{
    private $user;
    use MakeworklogTrait, MakeUserTrait, DatabaseMigrations;

    /**
     * @var worklogRepository
     */
    protected $worklogRepo;

    /**
     * @var App\Repositories\UserRepository
     */
    private $userRepo;

    public function setUp()
    {
        parent::setUp();
        $this->worklogRepo = App::make(worklogRepository::class);
        $this->userRepo = App::make(App\Repositories\UserRepository::class);
        $this->user = $this->userRepo->create($this->fakeUserData());
    }

    /**
     * @test create
     */
    public function testCreateworklog()
    {
        $worklog = $this->fakeworklogData(['user_id' => $this->user->id]);
        $createdworklog = $this->worklogRepo->create($worklog);
        $createdworklog = $createdworklog->toArray();
        $this->assertArrayHasKey('id', $createdworklog);
        $this->assertNotNull($createdworklog['id'], 'Created worklog must have id specified');
        $this->assertNotNull(Activity::find($createdworklog['id']), 'worklog with given id must be in DB');
        $this->assertModelData($worklog, $createdworklog);
    }

    /**
     * @test read
     */
    public function testReadworklog()
    {
        $worklog = $this->makeworklog(['user_id' => $this->user->id]);
        $dbworklog = $this->worklogRepo->find($worklog->id);
        $dbworklog = $dbworklog->toArray();
        $this->assertModelData($worklog->toArray(), $dbworklog);
    }

    /**
     * @test update
     */
    public function testUpdateworklog()
    {
        $worklog = $this->makeworklog(['user_id' => $this->user->id]);
        $fakeworklog = $this->fakeworklogData(['user_id' => $this->user->id]);
        $updatedworklog = $this->worklogRepo->update($fakeworklog, $worklog->id);
        $this->assertModelData($fakeworklog, $updatedworklog->toArray());
        $dbworklog = $this->worklogRepo->find($worklog->id);
        $this->assertModelData($fakeworklog, $dbworklog->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteworklog()
    {
        $worklog = $this->makeworklog(['user_id' => $this->user->id]);
        $resp = $this->worklogRepo->delete($worklog->id);
        $this->assertTrue($resp);
        $this->assertNull(Activity::find($worklog->id), 'worklog should not exist in DB');
    }
}
