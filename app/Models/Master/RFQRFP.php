<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFQRFP extends Model
{
    use HasFactory;

    public $table = 'rfq_rfp_masters';

    public $fillable = ['id'];
}
