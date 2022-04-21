<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustMstr extends Model
{
    use HasFactory;

    public $table = 'cust_mstr';

    protected $fillable = [
        'id'
    ];
}
