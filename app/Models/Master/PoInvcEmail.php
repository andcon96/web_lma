<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoInvcEmail extends Model
{
    use HasFactory;

    public $table = 'poinvc_email';

    protected $fillable = [
        'id'
    ];
}
