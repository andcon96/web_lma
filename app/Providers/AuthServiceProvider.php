<?php

namespace App\Providers;

use App\Models\Master\Role;
use App\Models\Transaksi\RcptUnplanned;
use App\Models\Transaksi\SuratJalan;
use App\Policies\RcptUnplannedPolicy;
use App\Policies\SuratJalanPolicy;
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
        SuratJalan::class => SuratJalanPolicy::class,
        RcptUnplanned::class => RcptUnplannedPolicy::class,
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
        // Menu HEADER
        //=============================
        Gate::define('access_po_head', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'PO');
        });

        Gate::define('access_sj_head', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SJ');
        });

        Gate::define('access_report_head', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'LP');
        });

        Gate::define('access_setting_head', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST');
        });

        Gate::define('access_dashboard_head', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'DS');
        });


        //=============================
        // Menu Transaksi
        //=============================

        Gate::define('access_transactions', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'TR');
        });

        Gate::define('po_receipt', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'PO01');
        });

        Gate::define('po_approval', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'PO02');
        });

        Gate::define('po_browse', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'PO03');
        });

        Gate::define('receipt_unplanned', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'PO04');
        });

        Gate::define('sj_create', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SJ01');
        });
        Gate::define('sj_browse', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SJ02');
        });
        Gate::define('sj_confirm', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'SJ03');
        });

        Gate::define('stock_item', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'LP01');
        });

        Gate::define('hutang_cust', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'LP02');
        });

        Gate::define('view_item', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'LP03');
        });

        Gate::define('view_dashboard', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'DS01');
        });


        


        //=============================
        // Menu Master
        //=============================
        Gate::define('access_masters', function ($user) {
            return $user->getRole->role === Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'MS');
        });

        Gate::define('user_maint', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST01');
        });

        Gate::define('role_maint', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST02');
        });

        Gate::define('rolemenu_maint', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST03');
        });

        Gate::define('wsaqx_maint', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST04');
        });

        Gate::define('email_maint', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST05');
        });

        Gate::define('dom_master', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST06');
        });

        Gate::define('cust_master', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST07');
        });

        Gate::define('loc_master', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST08');
        });

        Gate::define('site_master', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST09');
        });

        Gate::define('supp_master', function($user){
            return $user->getRole->role == Role::SUPER_USER || str_contains($user->getRoleType->accessmenu, 'ST10');
        });
    }
}