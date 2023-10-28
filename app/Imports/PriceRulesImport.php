<?php

namespace App\Imports;

use App\Models\PriceRules;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class PriceRulesImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            PriceRules::updateOrCreate(['kategori_produk'=> $row[0]],
                [
                'kategori_produk' => $row[0],
                'limit_1' => $row[1],
                'limit_2' => $row[2],
            ]);
        }
    }
}
