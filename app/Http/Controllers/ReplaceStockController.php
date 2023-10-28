<?php

namespace App\Http\Controllers;

use App\Exports\ExportStock;
use App\Imports\ImportStock;
use App\Models\JobApi;
use App\Models\Produk;
use App\Models\ReplaceStock;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

class ReplaceStockController extends Controller
{
    public function importView(Request $request){
        $replace_stock = ReplaceStock::get();
        // return view('importFile');
        return view('importFile', compact('replace_stock'));

    }

    public function import(Request $request){
        Excel::import(new ImportStock, $request->file('file')->store('files'));
        return redirect()->back();
    }

    public function exportUsers(Request $request){
        return Excel::download(new ExportStock, 'replace_stock.xlsx');
    }

    public function emptyTable(){
        ReplaceStock::truncate();
        $replace_stock = ReplaceStock::get();
        return response("Sukses");
        // return view('importFile', compact('replace_stock'));
    }

    public function replaceStock(){
        $replace_stock = ReplaceStock::get();
        
        foreach ($replace_stock as $item) {
            // $produk = Produk::where('sku', $item->sku)->first();

            try {
                $response = Http::put('https://pos.isitaman.com/product/replaceStockBySku', [
                    'username' => 'isitaman',
                    'password' => '1s1t4m4nJ@v1n4.',
                    'stock' => $item->count,
                    'sku' => $item->sku,
                ])['data'];
                
                $data  = [
                    'sku' => $response['sku'],
                    'stock' => $response['stock']
                ];
        
                // $produk->stock = $response['stock'];
                // $produk->stock_isitaman = $response['stock'];
                // $produk->isSync = 1;
                // $produk->update();
                $item->delete();

            } catch (\Throwable $th) {

                // $newJob = new JobApi();
                // $newJob->sku = $item->sku;
                // $newJob->count = $item->count;
                // $newJob->action = 'replace';
                // $newJob->endpoint = 'https://pos.isitaman.com/product/replaceStockBySku';
                // $newJob->status = 0;
                // $newJob->updated_at = Carbon::now();
                // $newJob->save();
    
                // $produk->isSync = 0;
                // $produk->update();
                
                // return response()->json('Gagal Mengirim data ke IsiTaman', 500);
            }   
        }

        // return response("Selesai");
        return view('importFile', compact('replace_stock'));
    }

}
