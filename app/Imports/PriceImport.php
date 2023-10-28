<?php

namespace App\Imports;

use App\Models\Price;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;


class PriceImport implements ToCollection
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
            Price::updateOrCreate(['sku_produk'=> $row[0]],
                [
                'sku_produk' => $row[0],
                'harga_1' => $row[1],
                'harga_2' => $row[2],
                'harga_3' => $row[3],
            ]);
        }
    }
}
