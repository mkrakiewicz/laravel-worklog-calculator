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

class ActivityExceededActivitiesCriteria implements CriteriaInterface
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
        $withExceededActivities = $this->request->get('withExceededActivities', null);
        if ($withExceededActivities) {
            $settingId = Setting::where('key', '=', Setting::MINUTES_PER_DAY_SETTING)->first()->id;
            $model = $model->addSelect(['activities.*', DB::raw('(sums.activities_sum > us.value) as exceeded_activities')]);
            /** @var Builder $model */
            $model = $model->join(
                DB::raw('users_activities_settings us'), function ($join) use ($settingId) {
                /** @var JoinClause $join */
                $join->on('us.user_id', '=', 'activities.user_id');
                $join->on('us.setting_id', '=', DB::raw($settingId));
            }, null, null, 'left');
            $model = $model->join(DB::raw('( select user_id,DATE(`time`) as `date`,
             sum(activities) as activities_sum from activities group by activities.user_id,DATE(`time`) ) sums'),
                function ($join) {
                    /** @var JoinClause $join */
                    $join->on('sums.user_id', '=', 'activities.user_id');
                    $join->on('sums.date', '=', DB::raw('DATE(activities.`time`)'));
                }, null, null, 'left');
        }
        return $model;
    }
}
