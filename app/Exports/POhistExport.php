<?php

namespace App\Exports;

use App\Models\Export\ExportPO;
use App\Models\PoHist;
use Maatwebsite\Excel\Concerns\FromCollection;

class POhistExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ExportPO::all();
    }
}
