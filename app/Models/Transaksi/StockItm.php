<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItm extends Model
{
    use HasFactory;

    public $table = 'stockitm';

    protected $fillable = [
        'id',
        'itemdom',
        'item_nbr',
        'item_loc',
    ];
}
