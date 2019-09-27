<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUsersActivitiesSettingsAPIRequest;
use App\Http\Requests\API\UpdateUsersActivitiesSettingsAPIRequest;
use App\Models\Role;
use App\Models\UsersActivitiesSettings;
use App\Repositories\UsersActivitiesSettingsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class UsersActivitiesSettingsController
 * @package App\Http\Controllers\API
 */
class UsersActivitiesSettingsAPIController extends AppBaseController
{
    /** @var  UsersActivitiesSettingsRepository */
    private $usersActivitiesSettingsRepository;

    public function __construct(UsersActivitiesSettingsRepository $usersActivitiesSettingsRepo)
    {
        $this->usersActivitiesSettingsRepository = $usersActivitiesSettingsRepo;
    }


    public function usersOverview()
    {
        $result = $this->usersActivitiesSettingsRepository->crossJoinSettingsWithUsers(
            Auth::user()->hasAccess([Role::CRUD_ALL_USER_SETTINGS])
        );

        return $this->sendResponse($result->toArray(), 'Available settings retrieved successfully');
    }

    /**
     * Display a listing of the UsersActivitiesSettings.
     * GET|HEAD /usersActivitiesSettings
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->usersActivitiesSettingsRepository->pushCriteria(new RequestCriteria($request));
        $this->usersActivitiesSettingsRepository->pushCriteria(new LimitOffsetCriteria($request));
        $usersActivitiesSettings = $this->usersActivitiesSettingsRepository->all();

        return $this->sendResponse($usersActivitiesSettings->toArray(), 'Users Activities Settings retrieved successfully');
    }

    /**
     * Store a newly created UsersActivitiesSettings in storage.
     * POST /usersActivitiesSettings
     *
     * @param CreateUsersActivitiesSettingsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUsersActivitiesSettingsAPIRequest $request)
    {
        $input = $request->all();

        $usersActivitiesSettings = $this->usersActivitiesSettingsRepository->create($input);

        return $this->sendResponse($usersActivitiesSettings->toArray(), 'Users Activities Settings saved successfully');
    }

    /**
     * Display the specified UsersActivitiesSettings.
     * GET|HEAD /usersActivitiesSettings/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var UsersActivitiesSettings $usersActivitiesSettings */
        $usersActivitiesSettings = $this->usersActivitiesSettingsRepository->findWithoutFail($id);

        if (empty($usersActivitiesSettings)) {
            return $this->sendError('Users Activities Settings not found');
        }

        return $this->sendResponse($usersActivitiesSettings->toArray(), 'Users Activities Settings retrieved successfully');
    }

    /**
     * Update the specified UsersActivitiesSettings in storage.
     * PUT/PATCH /usersActivitiesSettings/{id}
     *
     * @param  int $id
     * @param UpdateUsersActivitiesSettingsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUsersActivitiesSettingsAPIRequest $request)
    {
        $input = $request->all();

        /** @var UsersActivitiesSettings $usersActivitiesSettings */
        $usersActivitiesSettings = $this->usersActivitiesSettingsRepository->findWithoutFail($id);

        if (empty($usersActivitiesSettings)) {
            return $this->sendError('Users Activities Settings not found');
        }

        $usersActivitiesSettings = $this->usersActivitiesSettingsRepository->update($input, $id);

        return $this->sendResponse($usersActivitiesSettings->toArray(), 'UsersActivitiesSettings updated successfully');
    }

    /**
     * Remove the specified UsersActivitiesSettings from storage.
     * DELETE /usersActivitiesSettings/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var UsersActivitiesSettings $usersActivitiesSettings */
        $usersActivitiesSettings = $this->usersActivitiesSettingsRepository->findWithoutFail($id);

        if (empty($usersActivitiesSettings)) {
            return $this->sendError('Users Activities Settings not found');
        }

        $usersActivitiesSettings->delete();

        return $this->sendResponse($id, 'Users Activities Settings deleted successfully');
    }
}
