<?php

namespace App\Models\Master;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public $table = 'companies';
    public $timestamps = true;
    public $fillable = [
        'company_code', 'company_name', 'company_desc', 'company_img', 'company_last_sync',
        'company_email', 'created_at', 'updated_at'
    ];
}
