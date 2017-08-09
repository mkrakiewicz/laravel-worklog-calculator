<?php

namespace App\Repositories;

use App\Models\UsersCaloriesSettings;
use Auth;
use InfyOm\Generator\Common\BaseRepository;

class UsersCaloriesSettingsRepository extends BaseRepository
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
        return UsersCaloriesSettings::class;
    }

    public function crossJoinSettingsWithUsers($all = false)
    {
        $caloriesSettingsTable = 'users_calories_settings';

        $query = UsersCaloriesSettings::query()->
        select([
            "$caloriesSettingsTable.id", "settings.id as setting_id", "settings.key", "$caloriesSettingsTable.value",
            "users.id as user_id", "users.email"
        ])->from("settings")->
        crossJoin("users")->
        join("$caloriesSettingsTable", function ($join) use ($caloriesSettingsTable) {
            $join->on("$caloriesSettingsTable.user_id", "=", "users.id")
                ->on("$caloriesSettingsTable.setting_id", "=", "settings.id");
        }, null, null, "left")->
        orderBy("user_id", "asc");

        if ($all == false) {
            $query->where("users.id", "=", Auth::user()->id);
        }
        return $query->get();
    }
}
