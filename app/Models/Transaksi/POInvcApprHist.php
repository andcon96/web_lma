<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POInvcApprHist extends Model
{
    use HasFactory;

    public $table = 'poinvc_appr_hist';

    public $timestamps = false;

    protected $fillable = [
        'id'
    ];
}
