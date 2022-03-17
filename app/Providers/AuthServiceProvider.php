<?php

namespace App\Providers;

use App\Models\Master\Role;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('super_user', function ($user) {
            return $user->getRole->role === Role::SUPER_USER;
        });

        Gate::define('user', function ($user) {
            return $user->getRole->role === Role::User;
        });


        //============================
        // Dashboard
        //============================

        Gate::define('access_dashboard', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'HO01') ||
                $user->getRole->role === Role::SUPER_USER;
        });


        //=============================
        // Menu Transaksi
        //=============================

        Gate::define('po_receipt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'PO01');
        });

        //=============================
        // Menu Master
        //=============================
        Gate::define('access_masters', function ($user) {
            return $user->getRole->role === Role::SUPER_USER;
        });
    }
}