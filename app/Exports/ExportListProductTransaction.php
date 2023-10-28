<?php

namespace App\Exports;

use App\Models\ListProductTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportListProductTransaction implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ListProductTransaction::all();
        // return ListProductTransaction::select('sku','count')->get();

    }
}
