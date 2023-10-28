<?php

namespace App\Exports;

use App\Models\ReplaceStock;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportStock implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return ReplaceStock::all();
        return ReplaceStock::select('sku','count')->get();
    }
}
