<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POInvc extends Model
{
    use HasFactory;

    public $table = 'email_hist';

    protected $fillable = [
        'id'
    ];
}
