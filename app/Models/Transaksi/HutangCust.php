<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HutangCust extends Model
{
    use HasFactory;

    public $table = 'hutang_cust';

    protected $fillable = [
        'id',
        'hutangdom',
        'hutang_custnbr',
        'hutang_invcnbr',
    ];
}
