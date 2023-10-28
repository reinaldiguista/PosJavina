<?php

namespace App\Exports;

use App\Models\Price;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PriceExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Price::all();
        $type = DB::table('price')->select('sku_produk', 'harga_1', 'harga_2', 
        'harga_3')->get();
        return $type ;
    }

    public function headings(): array
    {
        return [
            'SKU',
            'HARGA 1',
            'HARGA 2',
            'HARGA 3'
        ];
    }
}
