<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ProductsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Produk::all();

        $type = DB::table('produk')->select('sku', 'nama_produk', 'kategori', 
        'jenis_produk', 'kondisi', 'genus', 'supplier', 'jenis_supplier',
        'registrasi_anggrek', 'grade', 'hb_sp', 'kelompok_pasar', 'link_foto',
        'kode_kebun', 'harga_terendah', 'hpp', 'stock', 'enable')->get();
        return $type ;
    }

    public function headings(): array
    {
        return [
            'SKU',
            'NAMA PRODUK',
            'KATEGORI',
            'JENIS_PRODUK',
            'KONDISI',
            'GENUS',
            'SUPPLIER',
            'JENIS SUPPLIER',
            'REGISTRASI ANGGREK',
            'GRADE',
            'HYBRID/SPESIES',
            'KELOMPOK PASAR',
            'LINK FOTO',
            'KODE KEBUN',
            'HARGA TERENDAH',
            'HPP',
            'STOK',
            'ENABLE',
        ];
    }
}
