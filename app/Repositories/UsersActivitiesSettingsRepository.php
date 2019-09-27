<?php

namespace App\Repositories;

use App\Models\UsersActivitiesSettings;
use Auth;
use InfyOm\Generator\Common\BaseRepository;

class UsersActivitiesSettingsRepository extends BaseRepository
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
        return UsersActivitiesSettings::class;
    }

    public function crossJoinSettingsWithUsers($all = false)
    {
        $activitiesSettingsTable = 'users_activities_settings';

        $query = UsersActivitiesSettings::query()->
        select([
            "$activitiesSettingsTable.id", "settings.id as setting_id", "settings.key", "$activitiesSettingsTable.value",
            "users.id as user_id", "users.email"
        ])->from("settings")->
        crossJoin("users")->
        join("$activitiesSettingsTable", function ($join) use ($activitiesSettingsTable) {
            $join->on("$activitiesSettingsTable.user_id", "=", "users.id")
                ->on("$activitiesSettingsTable.setting_id", "=", "settings.id");
        }, null, null, "left")->
        orderBy("user_id", "asc");

        if ($all == false) {
            $query->where("users.id", "=", Auth::user()->id);
        }
        return $query->get();
    }
}
