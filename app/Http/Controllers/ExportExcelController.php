<?php

namespace App\Http\Controllers;

use App\Exports\PriceExport;
use App\Exports\PriceRulesExport;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Imports\PriceImport;
use App\Imports\PriceRulesImport;
use App\Imports\ProdukImport;
use App\Models\ProdukNew;
use DB;
use Excel;

class ExportExcelController extends Controller
{
    function index()
    {
     $product_data = DB::table('produk')->get();
     return view('export_excel')->with('product_data', $product_data);
    }

    function excel()
    {
     $product_data = DB::table('produk')->get()->toArray();
     $product_array[] = array('sku', 'nama_produk', 'stock');
     foreach($product_data as $product)
     {
      $product_array[] = array(
       'sku'  => $product->sku,
       'title'   => $product->title,
       'stock'    => $product->stock,
      );
     }
     Excel::create('product Data', function($excel) use ($product_array){
      $excel->setTitle('product Data');
      $excel->sheet('product Data', function($sheet) use ($product_array){
       $sheet->fromArray($product_array, null, 'A1', false, false);
      });
     })->download('xlsx');

     
    }

    public function export() 
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function export_price() 
    {
        return Excel::download(new PriceExport, 'export_price.xlsx');
    }
    public function export_price_rules() 
    {
        return Excel::download(new PriceRulesExport, 'export_price_rules.xlsx');
    }

    public function report() 
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function import(Request $request) 
    {
        $file = $request->file('file');
        $namaFile = $file->getClientOriginalName();
        $file->move('DataProduk', $namaFile);
        Excel::import(new ProdukImport, public_path('/DataProduk/'.$namaFile));

        $kategori = ProdukNew::select('kategori')->groupBy('kategori')->get();
        $jenis_produk = ProdukNew::select('jenis_produk')->groupBy('jenis_produk')->get();
        $kondisi = ProdukNew::select('kondisi')->groupBy('kondisi')->get();
        $genus = ProdukNew::select('genus')->groupBy('genus')->get();
        $supplier = ProdukNew::select('supplier')->groupBy('supplier')->get();
        $jenis_supplier = ProdukNew::select('jenis_supplier')->groupBy('jenis_supplier')->get();
        $registrasi_anggrek = ProdukNew::select('registrasi_anggrek')->groupBy('registrasi_anggrek')->get();
        $grade = ProdukNew::select('grade')->groupBy('grade')->get();
        $hb_sp = ProdukNew::select('hb_sp')->groupBy('hb_sp')->get();
        $kelompok_pasar = ProdukNew::select('kelompok_pasar')->groupBy('kelompok_pasar')->get();
        $enable = ProdukNew::select('enable')->groupBy('enable')->get();
        
        return view('produk.index', compact('kategori','jenis_produk','kondisi','genus','supplier','jenis_supplier','registrasi_anggrek','grade','hb_sp','kelompok_pasar','enable'));
        // return view('produk.index');

    }

    public function import_price(Request $request) 
    {
        $file = $request->file('file');
        $namaFile = $file->getClientOriginalName();
        $file->move('DataProduk', $namaFile);
        Excel::import(new PriceImport, public_path('/DataProduk/'.$namaFile));
        $sku = ProdukNew::select('sku')->groupBy('sku')->get();

        return view('price.index', compact('sku'));

    }

    public function import_price_rules(Request $request) 
    {
        $file = $request->file('file');
        $namaFile = $file->getClientOriginalName();
        $file->move('DataProduk', $namaFile);
        Excel::import(new PriceRulesImport, public_path('/DataProduk/'.$namaFile));
        $kategori = ProdukNew::select('kategori')->groupBy('kategori')->get();

        return view('price_rules.index', compact('kategori'));

    }
}

?>