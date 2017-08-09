<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRoleUsersAPIRequest;
use App\Http\Requests\API\UpdateRoleUsersAPIRequest;
use App\Models\RoleUsers;
use App\Repositories\RoleUsersRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class RoleUsersController
 * @package App\Http\Controllers\API
 */

class RoleUsersAPIController extends AppBaseController
{
    /** @var  RoleUsersRepository */
    private $roleUsersRepository;

    public function __construct(RoleUsersRepository $roleUsersRepo)
    {
        $this->roleUsersRepository = $roleUsersRepo;
    }

    /**
     * Display a listing of the RoleUsers.
     * GET|HEAD /roleUsers
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->roleUsersRepository->pushCriteria(new RequestCriteria($request));
        $this->roleUsersRepository->pushCriteria(new LimitOffsetCriteria($request));
        $roleUsers = $this->roleUsersRepository->all();

        return $this->sendResponse($roleUsers->toArray(), 'Role Users retrieved successfully');
    }

    /**
     * Store a newly created RoleUsers in storage.
     * POST /roleUsers
     *
     * @param CreateRoleUsersAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleUsersAPIRequest $request)
    {
        $input = $request->all();

        $roleUsers = $this->roleUsersRepository->create($input);

        return $this->sendResponse($roleUsers->toArray(), 'Role Users saved successfully');
    }

    /**
     * Display the specified RoleUsers.
     * GET|HEAD /roleUsers/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RoleUsers $roleUsers */
        $roleUsers = $this->roleUsersRepository->findWithoutFail($id);

        if (empty($roleUsers)) {
            return $this->sendError('Role Users not found');
        }

        return $this->sendResponse($roleUsers->toArray(), 'Role Users retrieved successfully');
    }

    /**
     * Update the specified RoleUsers in storage.
     * PUT/PATCH /roleUsers/{id}
     *
     * @param  int $id
     * @param UpdateRoleUsersAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleUsersAPIRequest $request)
    {
        $input = $request->all();

        /** @var RoleUsers $roleUsers */
        $roleUsers = $this->roleUsersRepository->findWithoutFail($id);

        if (empty($roleUsers)) {
            return $this->sendError('Role Users not found');
        }

        $roleUsers = $this->roleUsersRepository->update($input, $id);

        return $this->sendResponse($roleUsers->toArray(), 'RoleUsers updated successfully');
    }

    /**
     * Remove the specified RoleUsers from storage.
     * DELETE /roleUsers/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RoleUsers $roleUsers */
        $roleUsers = $this->roleUsersRepository->findWithoutFail($id);

        if (empty($roleUsers)) {
            return $this->sendError('Role Users not found');
        }

        $roleUsers->delete();

        return $this->sendResponse($id, 'Role Users deleted successfully');
    }
}
