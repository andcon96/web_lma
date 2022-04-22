<?php

namespace App\Models\Transaksi;

use App\Models\Master\CustMstr;
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

    public function getDetailCust(){
        return $this->hasOne(CustMstr::class,'cust_code','sj_so_cust');
    }

    public function getDetailShip(){
        return $this->hasOne(CustMstr::class,'cust_code','sj_so_ship');
    }

    public function getDetailBill(){
        return $this->hasOne(CustMstr::class,'cust_code', 'sj_so_bill');
    }

    public function sumQtyInput(){
        return $this->hasMany(SuratJalanDetail::class);
    }

}
