<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateactivityAPIRequest;
use App\Http\Requests\API\UpdateactivityAPIRequest;
use App\Models\Activity;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Criteria\ActivityDateTimeCriteria;
use App\Repositories\Criteria\ActivityExceededActivitiesCriteria;
use App\Repositories\ActivityRepository;
use Auth;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class activityController
 * @package App\Http\Controllers\API
 */
class activityAPIController extends AppBaseController
{
    /** @var  ActivityRepository */
    private $activityRepository;

    /**
     * @var SessionGuard
     */
    private $auth;

    public function __construct(ActivityRepository $activityRepo, AuthManager $auth )
    {
        $this->activityRepository = $activityRepo;
        $this->auth = $auth;
    }

    /**
     * Display a listing of the activity.
     * GET|HEAD /activities
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($this->getAuthUser()->hasAccess([Role::CRUD_ALL_ACTIVITIES]) == false) {
            $id = $this->auth->id();
            $request['search'] = "user_id:$id";
        }
        $this->activityRepository->pushCriteria(new ActivityDateTimeCriteria($request));
        $this->activityRepository->pushCriteria(new ActivityExceededActivitiesCriteria($request));
        $this->activityRepository->pushCriteria(new RequestCriteria($request));
        $this->activityRepository->pushCriteria(new LimitOffsetCriteria($request));

        $activities = $this->activityRepository->all();

        return $this->sendResponse($activities->toArray(), 'activities retrieved successfully');
    }

    /**
     * Store a newly created activity in storage.
     * POST /activities
     *
     * @param CreateactivityAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateactivityAPIRequest $request)
    {
        if (empty($request['user_id']) || ($this->getAuthUser()->hasAccess([Role::CRUD_ALL_ACTIVITIES]) == false)) {
            $request['user_id'] = $this->auth->id();
        }
        $input = $request->all();

        $activities = $this->activityRepository->create($input);

        return $this->sendResponse($activities->toArray(), 'activity saved successfully');
    }

    /**
     * Display the specified activity.
     * GET|HEAD /activities/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Activity $activity */
        $activity = $this->activityRepository->findWithoutFail($id);

        if (empty($activity)) {
            return $this->sendError('activity not found');
        }

        return $this->sendResponse($activity->toArray(), 'activity retrieved successfully');
    }

    /**
     * Update the specified activity in storage.
     * PUT/PATCH /activities/{id}
     *
     * @param  int $id
     * @param UpdateactivityAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateactivityAPIRequest $request)
    {
        if ($this->getAuthUser()->hasAccess([Role::CRUD_ALL_ACTIVITIES]) == false) {
            $request['user_id'] = $this->auth->id();
        }

        $input = $request->all();

        /** @var Activity $activity */
        $activity = $this->activityRepository->findWithoutFail($id);

        if (empty($activity)) {
            return $this->sendError('activity not found');
        }

        $activity = $this->activityRepository->update($input, $id);

        return $this->sendResponse($activity->toArray(), 'activity updated successfully');
    }

    /**
     * Remove the specified activity from storage.
     * DELETE /activities/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Activity $activity */
        $activity = $this->activityRepository->findWithoutFail($id);

        if (empty($activity)) {
            return $this->sendError('activity not found');
        }

        $activity->delete();

        return $this->sendResponse($id, 'activity deleted successfully');
    }

    /**
     * @return User
     */
    private function getAuthUser()
    {
        return $this->auth->user();
    }
}
