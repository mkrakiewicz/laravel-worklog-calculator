<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class MealDateTimeCriteria implements CriteriaInterface
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
        $timeFrom = $this->request->get('timeFrom', null);
        $timeTo = $this->request->get('timeTo', null);
        $dateFrom = $this->request->get('dateFrom', null);
        $dateTo = $this->request->get('dateTo', null);
        $castRaw = DB::raw('CAST(`time` as time)');
        if ($timeFrom) {
            $model = $model->where($castRaw, '>=', $timeFrom);
        }
        if ($timeTo) {
            $model = $model->where($castRaw, '<', $timeTo);
        }

        if ($dateFrom) {
            $model = $model->where('time', '>=', $dateFrom);
        }
        if ($dateTo) {
            $model = $model->where('time', '<', $dateTo);
        }

        return $model;
    }
}