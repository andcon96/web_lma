<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocMstr extends Model
{
    use HasFactory;

    public $table = 'loc_mstr';

    protected $fillable = [
        'id',
        'loc',
        'loc_domain',
    ];
}
