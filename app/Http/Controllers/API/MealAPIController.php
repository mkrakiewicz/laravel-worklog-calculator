<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMealAPIRequest;
use App\Http\Requests\API\UpdateMealAPIRequest;
use App\Models\Meal;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Criteria\MealDateTimeCriteria;
use App\Repositories\Criteria\MealExceededCaloriesCriteria;
use App\Repositories\MealRepository;
use Auth;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class MealController
 * @package App\Http\Controllers\API
 */
class MealAPIController extends AppBaseController
{
    /** @var  MealRepository */
    private $mealRepository;

    /**
     * @var SessionGuard
     */
    private $auth;

    public function __construct(MealRepository $mealRepo, AuthManager $auth )
    {
        $this->mealRepository = $mealRepo;
        $this->auth = $auth;
    }

    /**
     * Display a listing of the Meal.
     * GET|HEAD /meals
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($this->getAuthUser()->hasAccess([Role::CRUD_ALL_MEALS]) == false) {
            $id = $this->auth->id();
            $request['search'] = "user_id:$id";
        }
        $this->mealRepository->pushCriteria(new MealDateTimeCriteria($request));
        $this->mealRepository->pushCriteria(new MealExceededCaloriesCriteria($request));
        $this->mealRepository->pushCriteria(new RequestCriteria($request));
        $this->mealRepository->pushCriteria(new LimitOffsetCriteria($request));

        $meals = $this->mealRepository->all();

        return $this->sendResponse($meals->toArray(), 'Meals retrieved successfully');
    }

    /**
     * Store a newly created Meal in storage.
     * POST /meals
     *
     * @param CreateMealAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMealAPIRequest $request)
    {
        if (empty($request['user_id']) || ($this->getAuthUser()->hasAccess([Role::CRUD_ALL_MEALS]) == false)) {
            $request['user_id'] = $this->auth->id();
        }
        $input = $request->all();

        $meals = $this->mealRepository->create($input);

        return $this->sendResponse($meals->toArray(), 'Meal saved successfully');
    }

    /**
     * Display the specified Meal.
     * GET|HEAD /meals/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Meal $meal */
        $meal = $this->mealRepository->findWithoutFail($id);

        if (empty($meal)) {
            return $this->sendError('Meal not found');
        }

        return $this->sendResponse($meal->toArray(), 'Meal retrieved successfully');
    }

    /**
     * Update the specified Meal in storage.
     * PUT/PATCH /meals/{id}
     *
     * @param  int $id
     * @param UpdateMealAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMealAPIRequest $request)
    {
        if ($this->getAuthUser()->hasAccess([Role::CRUD_ALL_MEALS]) == false) {
            $request['user_id'] = $this->auth->id();
        }

        $input = $request->all();

        /** @var Meal $meal */
        $meal = $this->mealRepository->findWithoutFail($id);

        if (empty($meal)) {
            return $this->sendError('Meal not found');
        }

        $meal = $this->mealRepository->update($input, $id);

        return $this->sendResponse($meal->toArray(), 'Meal updated successfully');
    }

    /**
     * Remove the specified Meal from storage.
     * DELETE /meals/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Meal $meal */
        $meal = $this->mealRepository->findWithoutFail($id);

        if (empty($meal)) {
            return $this->sendError('Meal not found');
        }

        $meal->delete();

        return $this->sendResponse($id, 'Meal deleted successfully');
    }

    /**
     * @return User
     */
    private function getAuthUser()
    {
        return $this->auth->user();
    }
}
