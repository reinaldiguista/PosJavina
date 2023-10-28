<?php

namespace App\Exports;

use App\Models\PriceRules;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PriceRulesExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return PriceRules::all();
        $type = DB::table('price_rules')->select('kategori_produk', 'limit_1', 'limit_2')->get();
        return $type ;
        
    }

    public function headings(): array
    {
        return [
            'SKU',
            'LIMIT 1',
            'LIMIT 2',
        ];
    }
}
