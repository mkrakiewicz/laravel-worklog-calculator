<?php

namespace App\Providers;

use App\Models\Meal;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();

        $this->registerPostPolicies();

    }

    public function registerPostPolicies()
    {
        $apiPermissions = [
            'access-user-settings' => function ($user) {
                /* @var \App\Models\User $user */
                return $user->hasAccess([Role::ACCESS_USER_SETTINGS]);
            },
            'modify-user-settings' => function ($user) {
                /* @var \App\Models\User $user */
                return $user->hasAccess([Role::CRUD_ALL_USER_SETTINGS]) OR
                ($user->hasAccess([Role::CRUD_OWN_USER_SETTINGS]));// and ($user->id == $userCaloriesSetting->user_id));
            },
            'access-users' => function ($user) {
                /* @var \App\Models\User $user */
                return $user->hasAccess([Role::ACCESS_USERS]);
            },
            'modify-users' => function ($user) {
                /* @var \App\Models\User $user */
                return $user->hasAccess([Role::CRUD_ALL_USERS]);
            },
            'access-meals' => function ($user) {
                /* @var \App\Models\User $user */
                return $user->hasAccess([Role::ACCESS_MEALS]);
            },
            'modify-meals' => function ($user, $mealId) {
                $meal = Meal::findOrFail($mealId);
                /* @var \App\Models\User $user */
                return $user->hasAccess([Role::CRUD_ALL_MEALS]) OR
                ($user->hasAccess([Role::CRUD_OWN_MEALS]) and ($user->id == $meal->user_id));
            },
        ];

        foreach ($apiPermissions as $name => $function) {
            Gate::define($name, $function);
        }
    }
}
