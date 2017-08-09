<?php

namespace App\Repositories\Criteria;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class MealExceededCaloriesCriteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param Builder|Model $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $withExceededCalories = $this->request->get('withExceededCalories', null);
        if ($withExceededCalories) {
            $settingId = Setting::where('key', '=', Setting::CALORIES_PER_DAY_SETTING)->first()->id;
            $model = $model->addSelect(['meals.*', DB::raw('(sums.calories_sum > us.value) as exceeded_calories')]);
            /** @var Builder $model */
            $model = $model->join(
                DB::raw('users_calories_settings us'), function ($join) use ($settingId) {
                /** @var JoinClause $join */
                $join->on('us.user_id', '=', 'meals.user_id');
                $join->on('us.setting_id', '=', DB::raw($settingId));
            }, null, null, 'left');
            $model = $model->join(DB::raw('( select user_id,DATE(`time`) as `date`,
             sum(calories) as calories_sum from meals group by meals.user_id,DATE(`time`) ) sums'),
                function ($join) {
                    /** @var JoinClause $join */
                    $join->on('sums.user_id', '=', 'meals.user_id');
                    $join->on('sums.date', '=', DB::raw('DATE(meals.`time`)'));
                }, null, null, 'left');
        }
        return $model;
    }
}