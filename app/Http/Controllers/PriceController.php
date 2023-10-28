<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Produk;
use App\Models\ProdukNew;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sku = ProdukNew::select('sku')->groupBy('sku')->get();
        return view('price.index', compact('sku'));
    }

    public function data()
    {
        # code...
        $price = Price::get();

        return datatables()
            ->of($price)
            ->addColumn('sku', function ($price) {
                return '<span class="label label-primary">'. $price->sku_produk .'</span>';
            })
            ->addColumn('harga_1', function ($price) {
                return format_uang($price->harga_1);
            })
            ->addColumn('harga_2', function ($price) {
                return format_uang($price->harga_2);
            })
            ->addColumn('harga_3', function ($price) {
                return format_uang($price->harga_3);
            })
            ->addColumn('aksi', function ($price) {
                return '
                <div>
                    <button style="margin-top :5px" type="button" onclick="editForm(`'. route('price.update', $price->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-pencil"></i></button>
                    <button style="margin-top :5px" type="button" onclick="deleteData(`'. route('price.destroy', $price->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'sku'])
            ->make(true);
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
        $price = Price::create($request->all());
        $sku = ProdukNew::select('sku')->groupBy('sku')->get();
        return view('price.index', compact('sku'));
        // return response($request);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $price = Price::find($id);
        
        return response()->json($price);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //kontol kon cok
        $price = Price::find($id);
        $price->update($request->all());
        // $price->sku_produk = $request->sku_produk_edit;
        // $price->update();

        $sku = ProdukNew::select('sku')->groupBy('sku')->get();
        return view('price.index', compact('sku'));

        // return view('price.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $price = Price::find($id);
        $price->delete();

        return response(null, 204);
    }
}
