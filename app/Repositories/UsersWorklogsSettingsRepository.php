<?php

namespace App\Repositories;

use App\Models\UsersWorklogsSettings;
use Auth;
use InfyOm\Generator\Common\BaseRepository;

class UsersWorklogsSettingsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'setting_id',
        'value'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UsersWorklogsSettings::class;
    }

    public function crossJoinSettingsWithUsers($all = false)
    {
        $worklogsSettingsTable = 'users_worklogs_settings';

        $query = UsersWorklogsSettings::query()->
        select([
            "$worklogsSettingsTable.id", "settings.id as setting_id", "settings.key", "$worklogsSettingsTable.value",
            "users.id as user_id", "users.email"
        ])->from("settings")->
        crossJoin("users")->
        join("$worklogsSettingsTable", function ($join) use ($worklogsSettingsTable) {
            $join->on("$worklogsSettingsTable.user_id", "=", "users.id")
                ->on("$worklogsSettingsTable.setting_id", "=", "settings.id");
        }, null, null, "left")->
        orderBy("user_id", "asc");

        if ($all == false) {
            $query->where("users.id", "=", Auth::user()->id);
        }
        return $query->get();
    }
}
