<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUsersWorklogsSettingsAPIRequest;
use App\Http\Requests\API\UpdateUsersWorklogsSettingsAPIRequest;
use App\Models\Role;
use App\Models\UsersWorklogsSettings;
use App\Repositories\UsersWorklogsSettingsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class UsersWorklogsSettingsController
 * @package App\Http\Controllers\API
 */
class UsersWorklogsSettingsAPIController extends AppBaseController
{
    /** @var  UsersWorklogsSettingsRepository */
    private $usersWorklogsSettingsRepository;

    public function __construct(UsersWorklogsSettingsRepository $usersWorklogsSettingsRepo)
    {
        $this->usersWorklogsSettingsRepository = $usersWorklogsSettingsRepo;
    }


    public function usersOverview()
    {
        $result = $this->usersWorklogsSettingsRepository->crossJoinSettingsWithUsers(
            Auth::user()->hasAccess([Role::CRUD_ALL_USER_SETTINGS])
        );

        return $this->sendResponse($result->toArray(), 'Available settings retrieved successfully');
    }

    /**
     * Display a listing of the UsersWorklogsSettings.
     * GET|HEAD /usersWorklogsSettings
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->usersWorklogsSettingsRepository->pushCriteria(new RequestCriteria($request));
        $this->usersWorklogsSettingsRepository->pushCriteria(new LimitOffsetCriteria($request));
        $usersWorklogsSettings = $this->usersWorklogsSettingsRepository->all();

        return $this->sendResponse($usersWorklogsSettings->toArray(), 'Users Worklogs Settings retrieved successfully');
    }

    /**
     * Store a newly created UsersWorklogsSettings in storage.
     * POST /usersWorklogsSettings
     *
     * @param CreateUsersWorklogsSettingsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUsersWorklogsSettingsAPIRequest $request)
    {
        $input = $request->all();

        $usersWorklogsSettings = $this->usersWorklogsSettingsRepository->create($input);

        return $this->sendResponse($usersWorklogsSettings->toArray(), 'Users Worklogs Settings saved successfully');
    }

    /**
     * Display the specified UsersWorklogsSettings.
     * GET|HEAD /usersWorklogsSettings/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var UsersWorklogsSettings $usersWorklogsSettings */
        $usersWorklogsSettings = $this->usersWorklogsSettingsRepository->findWithoutFail($id);

        if (empty($usersWorklogsSettings)) {
            return $this->sendError('Users Worklogs Settings not found');
        }

        return $this->sendResponse($usersWorklogsSettings->toArray(), 'Users Worklogs Settings retrieved successfully');
    }

    /**
     * Update the specified UsersWorklogsSettings in storage.
     * PUT/PATCH /usersWorklogsSettings/{id}
     *
     * @param  int $id
     * @param UpdateUsersWorklogsSettingsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUsersWorklogsSettingsAPIRequest $request)
    {
        $input = $request->all();

        /** @var UsersWorklogsSettings $usersWorklogsSettings */
        $usersWorklogsSettings = $this->usersWorklogsSettingsRepository->findWithoutFail($id);

        if (empty($usersWorklogsSettings)) {
            return $this->sendError('Users Worklogs Settings not found');
        }

        $usersWorklogsSettings = $this->usersWorklogsSettingsRepository->update($input, $id);

        return $this->sendResponse($usersWorklogsSettings->toArray(), 'UsersWorklogsSettings updated successfully');
    }

    /**
     * Remove the specified UsersWorklogsSettings from storage.
     * DELETE /usersWorklogsSettings/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var UsersWorklogsSettings $usersWorklogsSettings */
        $usersWorklogsSettings = $this->usersWorklogsSettingsRepository->findWithoutFail($id);

        if (empty($usersWorklogsSettings)) {
            return $this->sendError('Users Worklogs Settings not found');
        }

        $usersWorklogsSettings->delete();

        return $this->sendResponse($id, 'Users Worklogs Settings deleted successfully');
    }
}
