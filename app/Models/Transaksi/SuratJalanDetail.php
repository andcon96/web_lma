<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratJalanDetail extends Model
{
    use HasFactory;

    public $table = 'sj_det';

    
    public function getMaster()
    {
        return $this->belongsTo(SuratJalan::class, 'sj_mstr_id');
    }


}
