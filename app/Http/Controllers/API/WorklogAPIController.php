<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateworklogAPIRequest;
use App\Http\Requests\API\UpdateworklogAPIRequest;
use App\Models\worklog;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Criteria\worklogDateTimeCriteria;
use App\Repositories\Criteria\worklogExceededWorklogsCriteria;
use App\Repositories\worklogRepository;
use Auth;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class worklogController
 * @package App\Http\Controllers\API
 */
class worklogAPIController extends AppBaseController
{
    /** @var  worklogRepository */
    private $worklogRepository;

    /**
     * @var SessionGuard
     */
    private $auth;

    public function __construct(worklogRepository $worklogRepo, AuthManager $auth )
    {
        $this->worklogRepository = $worklogRepo;
        $this->auth = $auth;
    }

    /**
     * Display a listing of the worklog.
     * GET|HEAD /worklogs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($this->getAuthUser()->hasAccess([Role::CRUD_ALL_worklogS]) == false) {
            $id = $this->auth->id();
            $request['search'] = "user_id:$id";
        }
        $this->worklogRepository->pushCriteria(new worklogDateTimeCriteria($request));
        $this->worklogRepository->pushCriteria(new worklogExceededWorklogsCriteria($request));
        $this->worklogRepository->pushCriteria(new RequestCriteria($request));
        $this->worklogRepository->pushCriteria(new LimitOffsetCriteria($request));

        $worklogs = $this->worklogRepository->all();

        return $this->sendResponse($worklogs->toArray(), 'worklogs retrieved successfully');
    }

    /**
     * Store a newly created worklog in storage.
     * POST /worklogs
     *
     * @param CreateworklogAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateworklogAPIRequest $request)
    {
        if (empty($request['user_id']) || ($this->getAuthUser()->hasAccess([Role::CRUD_ALL_worklogS]) == false)) {
            $request['user_id'] = $this->auth->id();
        }
        $input = $request->all();

        $worklogs = $this->worklogRepository->create($input);

        return $this->sendResponse($worklogs->toArray(), 'worklog saved successfully');
    }

    /**
     * Display the specified worklog.
     * GET|HEAD /worklogs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var worklog $worklog */
        $worklog = $this->worklogRepository->findWithoutFail($id);

        if (empty($worklog)) {
            return $this->sendError('worklog not found');
        }

        return $this->sendResponse($worklog->toArray(), 'worklog retrieved successfully');
    }

    /**
     * Update the specified worklog in storage.
     * PUT/PATCH /worklogs/{id}
     *
     * @param  int $id
     * @param UpdateworklogAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateworklogAPIRequest $request)
    {
        if ($this->getAuthUser()->hasAccess([Role::CRUD_ALL_worklogS]) == false) {
            $request['user_id'] = $this->auth->id();
        }

        $input = $request->all();

        /** @var worklog $worklog */
        $worklog = $this->worklogRepository->findWithoutFail($id);

        if (empty($worklog)) {
            return $this->sendError('worklog not found');
        }

        $worklog = $this->worklogRepository->update($input, $id);

        return $this->sendResponse($worklog->toArray(), 'worklog updated successfully');
    }

    /**
     * Remove the specified worklog from storage.
     * DELETE /worklogs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var worklog $worklog */
        $worklog = $this->worklogRepository->findWithoutFail($id);

        if (empty($worklog)) {
            return $this->sendError('worklog not found');
        }

        $worklog->delete();

        return $this->sendResponse($id, 'worklog deleted successfully');
    }

    /**
     * @return User
     */
    private function getAuthUser()
    {
        return $this->auth->user();
    }
}
