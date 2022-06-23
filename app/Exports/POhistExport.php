<?php

namespace App\Exports;

use App\Models\Export\ExportPO;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class POhistExport implements FromView, ShouldAutoSize
{
    public function view():View
    {
        $po = ExportPO::all();
        return view('export.pohist', [
            'po' => $po
        ]);
    }
    
}
