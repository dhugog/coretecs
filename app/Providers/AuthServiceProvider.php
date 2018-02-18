<?php

namespace CoreTecs\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'CoreTecs\Model' => 'CoreTecs\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPermissionsPolicies();
        //
    }

    public function registerPermissionsPolicies()
    {
        Gate::define('manage-stock', function ($user) {
            return $user->hasAccess(['manage-stock']);
        });

        Gate::define('manage-users', function ($user) {
            return $user->hasAccess(['manage-users']);
        });
    }
}
