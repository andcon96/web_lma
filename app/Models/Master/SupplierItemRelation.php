<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierItemRelation extends Model
{
    use HasFactory;

    public $table = 'supplier_item_relation';
    
    public function getSupplier()
    {
        return $this->belongsTo(Supplier::class, 'sir_supp_code');
    }

    public function getItem()
    {
        return $this->belongsTo(ItemInventoryMaster::class, 'sir_item_part');
    }
}
