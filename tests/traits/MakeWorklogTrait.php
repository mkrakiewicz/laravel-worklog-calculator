<?php

namespace Tests\Traits; 

use App;
use Faker\Factory as Faker;
use App\Models\worklog;
use App\Repositories\worklogRepository;

trait MakeworklogTrait
{
    /**
     * Create fake instance of worklog and save it in database
     *
     * @param array $worklogFields
     * @return worklog
     */
    public function makeworklog($worklogFields = [])
    {
        /** @var worklogRepository $worklogRepo */
        $worklogRepo = App::make(worklogRepository::class);
        $theme = $this->fakeworklogData($worklogFields);
        return $worklogRepo->create($theme);
    }

    /**
     * Get fake instance of worklog
     *
     * @param array $worklogFields
     * @return worklog
     */
    public function fakeworklog($worklogFields = [])
    {
        return new worklog($this->fakeworklogData($worklogFields));
    }

    /**
     * Get fake data of worklog
     *
     * @param array $postFields
     * @return array
     */
    public function fakeworklogData($worklogFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'worklogs' => $fake->numberBetween(50,450),
            'time' => $fake->time('Y-m-d H:i:s'),
            'user_id' => $fake->numberBetween(1,44)
        ], $worklogFields);
    }
}
