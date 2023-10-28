<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Stock;
use App\Models\StockDetail;
use Illuminate\Http\Request;
use Svg\Gradient\Stop;

class StockDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $stock = Stock::find($id);
        $produk = Produk::orderBy('title', 'asc')->get();
        $id_stock = $stock->id;
        return view('stock_detail.index', compact('produk', 'stock','id_stock'));
    }

    public function data($id)
    {
        $stock = Stock::where('id', $id)->first();
        
        $detail = StockDetail::
        where('status', 0)
        ->where('stock_id', $stock->id)                        
        ->get();

        $data = array();
        $total = 0;
        $total_item = 0;
        

        foreach ($detail as $item) {
            $produk = Produk::where('id', $item->product_id)->first();
            $item->sku = $produk->sku;
            $item->update();

            $row = array();
            $row['sku']             = '<span class="label label-success">'. $item->produk['sku'] .'</span';
            $row['title']           = $item->produk['title'];
            // $row['base_price']      = 'Rp. '. format_uang($item->base_price);
            $row['price']      = '<input type="number" class="form-control input-sm price" data-id="'. $item->id .'" value="'. $item->price .'">';
            $row['count']           = '<input type="number" class="form-control input-sm count" data-id="'. $item->id .'" value="'. $item->count .'">';
            $row['catatan']          = '<input type="text" class="form-control input-sm catatan" data-id="'. $item->id .'" value="'. $item->catatan .'">';
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('stock_detail.destroy', $item->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus produk</button>
                                </div>';  
            
            $data[] = $row;
            // $total += $item->final_price;
            $total_item += $item->count;
        }

        $stock->total_barang = $total_item;
        $stock->update();

        $data[] = [
            'sku' => '
                <div class="total_item hide">'. $total_item .'</div>',
            'title' => '',
            'price'  => '',
            'count'      => '',
            'catatan'    => '',
            'aksi'    => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'sku', 'count', 'price', 'catatan','total_item' ])
            ->make(true);
    }
    
    public function addProduct($sku, $count, $id_stock){
        
        $id_stock = $id_stock;
        $stock = Stock::where('id', $id_stock)->first();
        $produk = Produk::where('sku', $sku)->first();
        
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        // if ($produk->stock < $count) {
        //     $data  = [
        //         'status' => 'fail_stok',
        //     ]; 
        //     return response($data);
        // }

        $id = auth()->id();
        $detail = new StockDetail();
        $detail->stock_id = $id_stock;
        $detail->product_id = $produk->id;
        $detail->sku = $produk->sku;
        $detail->price = 0;
        // if ($member->customer_type == 1) {
        //     $detail->base_price = $produk->price;
        // } else if ($member->customer_type == 2) {
        //     $detail->base_price = $produk->offline_price;
        // } else if ($member->customer_type == 3) {
        //     $detail->base_price = $produk->reseller_price;
        // } else if ($member->customer_type == 4) {
        //     $detail->base_price = $produk->agen_price;
        // } else {
        //     # code...
        // }    

        $detail->count = $count;
        $detail->stock_before = $produk->stock;
        $detail->catatan = "";
        $detail->status = 0;
        $detail->save();


        return response()->json('Data berhasil ditambah', 200);
    
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockDetail  $stockDetail
     * @return \Illuminate\Http\Response
     */
    public function show(StockDetail $stockDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockDetail  $stockDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(StockDetail $stockDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockDetail  $stockDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockDetail $stockDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockDetail  $stockDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stockDetail = StockDetail::find($id);
        $stockDetail->delete();

        return response(null, 204);
    }
}
