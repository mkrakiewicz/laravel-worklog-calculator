<?php

namespace App\Observers;


use App\Models\User;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    /**
     * @var BcryptHasher
     */
    private $bcryptHasher;

    public function __construct(BcryptHasher $bcryptHasher)
    {
        $this->bcryptHasher = $bcryptHasher;
    }
    /**
     * Listen to the User created event.
     *
     * @param  User $user
     * @return void
     */
    public function creating(User $user)
    {
        $this->handleNewPasswordSave($user);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  User $user
     * @return void
     */
    public function updating(User $user)
    {
        $this->handleNewPasswordSave($user);
    }

    /**
     * @param $password
     * @return bool
     */
    private function isNewPassword($password)
    {
        return $this->bcryptHasher->check($password, $this->bcryptHasher->make($password));
    }

    /**
     * @param User $user
     */
    private function handleNewPasswordSave(User $user)
    {
        if ($this->isNewPassword($user->password)) {
            $user->password = $this->bcryptHasher->make($user->password);
        }
    }
}