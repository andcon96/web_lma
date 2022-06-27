<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItm extends Model
{
    use HasFactory;

    public $table = 'stockitm';

    protected $fillable = [
        'itemdom',
        'item_loc',
        'item_nbr',
        'item_desc',
        'item_um',
        'item_qtyoh',
    ];
}
