<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\ProdukNew;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;


class ProdukImport implements ToCollection
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
            ProdukNew::updateOrCreate(['sku'=> $row[0]],
                [
                'sku' => $row[0],
                'nama_produk' => $row[1],
                'kategori' => $row[2],
                'jenis_produk' => $row[3],
                'kondisi' => $row[4],
                'genus' => $row[5],
                'supplier' => $row[6],
                'jenis_supplier' => $row[7],
                'registrasi_anggrek' => $row[8],
                'grade' => $row[9],
                'hb_sp' => $row[10],
                'kelompok_pasar' => $row[11],
                'link_foto' => $row[12],
                'kode_kebun' => $row[13],
                'harga_terendah' => $row[14],
                'hpp' => $row[15],
                'stock' => $row[16],
                'enable' => $row[17],
            ]);
        }
    }
}
