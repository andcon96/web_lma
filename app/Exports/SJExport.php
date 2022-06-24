<?php

namespace App\Exports;

use App\Models\Transaksi\SuratJalan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SJExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $getsj = SuratJalan::with('getDetail','getDetailCust','getDetailShip')->get();

        return view('export.sj', [
            'sj' => $getsj,
        ]);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    
}
