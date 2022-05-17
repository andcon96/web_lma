<?php

namespace App\Policies;

use App\Models\Master\User;
use App\Models\Transaksi\SuratJalan;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

class SuratJalanPolicy
{
    use HandlesAuthorization;

    public function view(User $user, SuratJalan $suratJalan)
    {
        return $suratJalan->sj_domain == Session::get('domain') && $suratJalan->sj_status != 'New';
    }

    public function update(User $user, SuratJalan $suratJalan)
    {
        return $suratJalan->sj_domain == Session::get('domain') && $suratJalan->sj_status == 'New';
    }

    public function redo(User $user, SuratJalan $suratJalan)
    {
        return $suratJalan->sj_domain == Session::get('domain') && $suratJalan->sj_status == 'Cancelled';
    }

    
}
