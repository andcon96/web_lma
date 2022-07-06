<?php

namespace App\Policies;

use App\Models\Master\User;
use App\Models\Transaksi\RcptUnplanned;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

class RcptUnplannedPolicy
{
    use HandlesAuthorization;

    public function view(User $user, RcptUnplanned $rcptUnplanned)
    {
        return $rcptUnplanned->domain == Session::get('domain') && $rcptUnplanned->status == 'Open';
    }
}
