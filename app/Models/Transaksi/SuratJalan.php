<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratJalan extends Model
{
    use HasFactory;

    public $table = 'sj_mstr';

    public function getDetail()
    {
        return $this->hasMany(SuratJalanDetail::class, 'sj_mstr_id');
    }

}
