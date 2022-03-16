<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    public $table = 'sites';

    public function hasDomain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }
}
