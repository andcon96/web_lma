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

        Gate::define('supplier', function ($user) {
            return $user->getRole->role === Role::SUPPLIER;
        });

        Gate::define('purchasing', function ($user) {
            return $user->getRole->role === Role::PURCHASING;
        });

        //============================
        // Dashboard
        //============================

        Gate::define('access_dashboard', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'HO01') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::PURCHASING;
        });

        //============================
        // Menu PO
        //============================
        Gate::define('access_po', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                $user->getRole->role === Role::PURCHASING;
        });

        //============================
        // Sub Menu PO
        //============================
        Gate::define('access_po_list', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                // $user->getRole->role === Role::PURCHASING ||
                str_contains($user->getRoleType->accessmenu, 'PO01');
        });

        Gate::define('access_po_approval', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                // $user->getRole->role === Role::PURCHASING ||
                str_contains($user->getRoleType->accessmenu, 'PO03');
        });

        Gate::define('access_po_receipt_confirm', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                // $user->getRole->role === Role::PURCHASING ||
                str_contains($user->getRoleType->accessmenu, 'PO02');
        });

        Gate::define('access_po_utility', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                // $user->getRole->role === Role::PURCHASING ||
                str_contains($user->getRoleType->accessmenu, 'PO05');
        });

        Gate::define('access_po_history', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                // $user->getRole->role === Role::PURCHASING ||
                str_contains($user->getRoleType->accessmenu, 'PO06');
        });

        Gate::define('access_po_approval_history', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                // $user->getRole->role === Role::PURCHASING ||
                str_contains($user->getRoleType->accessmenu, 'PO07');
        });

        //============================
        // Menu RFQ
        //============================
        Gate::define('access_rfq', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                $user->getRole->role === Role::PURCHASING;
        });

        //=============================
        // Sub Menu Di RFQ
        //=============================
        Gate::define('access_rfq_data_mt', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'RF01') ||
                $user->getRole->role === Role::SUPER_USER;
        });
        Gate::define('access_rfq_approval_mt', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'RF02') ||
                $user->getRole->role === Role::SUPER_USER;
        });
        Gate::define('access_rfq_history_mt', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'RF06') ||
                $user->getRole->role === Role::SUPER_USER;
        });
        Gate::define('access_rfq_top_mt', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'RF04') ||
                $user->getRole->role === Role::SUPER_USER;
        });


        //============================
        // Menu RFP
        //============================
        Gate::define('access_rfp', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                $user->getRole->role === Role::PURCHASING;
        });

        //=============================
        // Sub Menu Di RFP
        //=============================
        Gate::define('access_rfp_data_mt', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'RFP01') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::PURCHASING;
        });

        Gate::define('access_rfp_approval', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'RFP02') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::PURCHASING;
        });

        Gate::define('access_rfp_history', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'RFP04') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::PURCHASING;
        });

        Gate::define('access_rfp_approval_history', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'RFP05') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::PURCHASING;
        });

        Gate::define('access_rfp_approval_utility', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'RFP06') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::PURCHASING;
        });

        //============================
        // Menu Supplier
        //============================
        Gate::define('access_supplier', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                $user->getRole->role === Role::SUPPLIER;
        });
        
        //=============================
        // Sub Menu Supplier
        //=============================
        Gate::define('access_po_confirmation', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'SH01') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::SUPPLIER;
        });
        Gate::define('access_shipper', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'SH02') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::SUPPLIER;
        });
        Gate::define('access_rfq_feed_back', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'SH03') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::SUPPLIER;
        });


        //============================
        // Sub Menu Supplier
        //============================
        Gate::define('access_po_confirmation', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                // $user->getRole->role === Role::SUPPLIER ||
                str_contains($user->getRoleType->accessmenu, 'SH01');
        });

        Gate::define('access_shipment', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                // $user->getRole->role === Role::SUPPLIER ||
                str_contains($user->getRoleType->accessmenu, 'SH02');
        });

        Gate::define('access_rfq_feedback', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                // $user->getRole->role === Role::SUPPLIER ||
                str_contains($user->getRoleType->accessmenu, 'SH03');
        });

        //=============================
        // Menu PP
        //=============================
        Gate::define('access_purchase_plan', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                $user->getRole->role === Role::PURCHASING;
        });
        //=============================
        // Sub Menu PP
        //=============================
        Gate::define('access_pp_list', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'PP01') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::PURCHASING;
        });

        Gate::define('access_pp_create',  function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'PP02') ||
                $user->getRole->role === Role::SUPER_USER;
            // $user->getRole->role === Role::PURCHASING;
        });


        //=============================
        // Menu Inventory
        //=============================
        Gate::define('access_inventory', function ($user) {
            return
                $user->getRole->role === Role::SUPER_USER ||
                $user->getRole->role === Role::PURCHASING ||
                $user->getRole->role === Role::SUPPLIER;
        });
        //=============================
        // Sub Menu Inventory
        //=============================
        Gate::define('access_safety_stock', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'IV01') ||
                $user->getRole->role === Role::SUPER_USER;
        });
        Gate::define('access_expired_inv', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'IV02') ||
                $user->getRole->role === Role::SUPER_USER;
        });

        Gate::define('access_slow_moving', function ($user) {
            return
                str_contains($user->getRoleType->accessmenu, 'IV03') ||
                $user->getRole->role === Role::SUPER_USER;
        });


        //=============================
        // Menu Master
        //=============================
        Gate::define('access_masters', function ($user) {
            return $user->getRole->role === Role::SUPER_USER;
        });
    }
}