<?php

namespace App;


use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;

class CustomBearerTokenResponse extends BearerTokenResponse
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserIdBearerTokenResponse constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function getExtraParams(AccessTokenEntityInterface $accessToken)
    {
        $id = $this->accessToken->getUserIdentifier();
        /** @var User $user */
        $user = $this->userRepository->find($id);
        return [
            'acl' => [
                'access_worklogs' => $user->hasAccess([Role::ACCESS_worklogS]),
                'access_users' => $user->hasAccess([Role::ACCESS_USERS]),
                'access_user_settings' => $user->hasAccess([Role::ACCESS_USER_SETTINGS]),
                'crud_all_worklogs' => $user->hasAccess([Role::CRUD_ALL_worklogS]),
                'crud_all_user_settings' => $user->hasAccess([Role::CRUD_ALL_USER_SETTINGS]),
            ],
            'user' => $user->toArray()
        ];
    }
}