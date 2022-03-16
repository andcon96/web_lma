<?php

//==============================
// Digabung dengan Item RFQ Master
//==============================

namespace App\Models\Master;

use App\Models\RFPDets;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInventoryMaster extends Model
{
    use HasFactory;

    public $table = 'item_inventory_masters';

    public function hasRFPDets()
    {
        return $this->hasMany(RFPDets::class, 'rfp_item_id');
    }
}
