<?php

namespace App\Imports;

use App\Models\ReplaceStock;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportStock implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ReplaceStock([
            'sku' => $row[0],
            'count' => $row[1],
        ]);
    }
}
