<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUsersCaloriesSettingsAPIRequest;
use App\Http\Requests\API\UpdateUsersCaloriesSettingsAPIRequest;
use App\Models\Role;
use App\Models\UsersCaloriesSettings;
use App\Repositories\UsersCaloriesSettingsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class UsersCaloriesSettingsController
 * @package App\Http\Controllers\API
 */
class UsersCaloriesSettingsAPIController extends AppBaseController
{
    /** @var  UsersCaloriesSettingsRepository */
    private $usersCaloriesSettingsRepository;

    public function __construct(UsersCaloriesSettingsRepository $usersCaloriesSettingsRepo)
    {
        $this->usersCaloriesSettingsRepository = $usersCaloriesSettingsRepo;
    }


    public function usersOverview()
    {
        $result = $this->usersCaloriesSettingsRepository->crossJoinSettingsWithUsers(
            Auth::user()->hasAccess([Role::CRUD_ALL_USER_SETTINGS])
        );

        return $this->sendResponse($result->toArray(), 'Available settings retrieved successfully');
    }

    /**
     * Display a listing of the UsersCaloriesSettings.
     * GET|HEAD /usersCaloriesSettings
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->usersCaloriesSettingsRepository->pushCriteria(new RequestCriteria($request));
        $this->usersCaloriesSettingsRepository->pushCriteria(new LimitOffsetCriteria($request));
        $usersCaloriesSettings = $this->usersCaloriesSettingsRepository->all();

        return $this->sendResponse($usersCaloriesSettings->toArray(), 'Users Calories Settings retrieved successfully');
    }

    /**
     * Store a newly created UsersCaloriesSettings in storage.
     * POST /usersCaloriesSettings
     *
     * @param CreateUsersCaloriesSettingsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUsersCaloriesSettingsAPIRequest $request)
    {
        $input = $request->all();

        $usersCaloriesSettings = $this->usersCaloriesSettingsRepository->create($input);

        return $this->sendResponse($usersCaloriesSettings->toArray(), 'Users Calories Settings saved successfully');
    }

    /**
     * Display the specified UsersCaloriesSettings.
     * GET|HEAD /usersCaloriesSettings/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var UsersCaloriesSettings $usersCaloriesSettings */
        $usersCaloriesSettings = $this->usersCaloriesSettingsRepository->findWithoutFail($id);

        if (empty($usersCaloriesSettings)) {
            return $this->sendError('Users Calories Settings not found');
        }

        return $this->sendResponse($usersCaloriesSettings->toArray(), 'Users Calories Settings retrieved successfully');
    }

    /**
     * Update the specified UsersCaloriesSettings in storage.
     * PUT/PATCH /usersCaloriesSettings/{id}
     *
     * @param  int $id
     * @param UpdateUsersCaloriesSettingsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUsersCaloriesSettingsAPIRequest $request)
    {
        $input = $request->all();

        /** @var UsersCaloriesSettings $usersCaloriesSettings */
        $usersCaloriesSettings = $this->usersCaloriesSettingsRepository->findWithoutFail($id);

        if (empty($usersCaloriesSettings)) {
            return $this->sendError('Users Calories Settings not found');
        }

        $usersCaloriesSettings = $this->usersCaloriesSettingsRepository->update($input, $id);

        return $this->sendResponse($usersCaloriesSettings->toArray(), 'UsersCaloriesSettings updated successfully');
    }

    /**
     * Remove the specified UsersCaloriesSettings from storage.
     * DELETE /usersCaloriesSettings/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var UsersCaloriesSettings $usersCaloriesSettings */
        $usersCaloriesSettings = $this->usersCaloriesSettingsRepository->findWithoutFail($id);

        if (empty($usersCaloriesSettings)) {
            return $this->sendError('Users Calories Settings not found');
        }

        $usersCaloriesSettings->delete();

        return $this->sendResponse($id, 'Users Calories Settings deleted successfully');
    }
}
