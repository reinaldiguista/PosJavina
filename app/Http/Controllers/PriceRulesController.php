<?php

namespace App\Http\Controllers;

use App\Models\PriceRules;
use App\Models\ProdukNew;
use Illuminate\Http\Request;

class PriceRulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $kategori = ProdukNew::select('kategori')->groupBy('kategori')->get();
        return view('price_rules.index', compact('kategori'));
        // return view('price_rules.index');

    }

    public function data()
    {
        $priceRules = PriceRules::get();

        return datatables()
            ->of($priceRules)
            ->addColumn('kategori', function ($priceRules) {
                return $priceRules->kategori_produk;
            })
            ->addColumn('limit_1', function ($priceRules) {
                return format_uang($priceRules->limit_1);
            })
            ->addColumn('limit_2', function ($priceRules) {
                return format_uang($priceRules->limit_2);
            })
            ->addColumn('aksi', function ($priceRules) {
                return '
                <div>
                    <button style="margin-top :5px" type="button" onclick="editForm(`'. route('price_rules.update', $priceRules->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-pencil"></i></button>
                    <button style="margin-top :5px" type="button" onclick="deleteData(`'. route('price_rules.destroy', $priceRules->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i></button>
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
        $priceRules = PriceRules::create($request->all());

        $kategori = ProdukNew::select('kategori')->groupBy('kategori')->get();
        return view('price_rules.index', compact('kategori'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PriceRules  $priceRules
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $priceRules = PriceRules::find($id);
        
        return response()->json($priceRules);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PriceRules  $priceRules
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceRules $priceRules)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PriceRules  $priceRules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $priceRules = PriceRules::find($id);
        $priceRules->update($request->all());

        $kategori = ProdukNew::select('kategori')->groupBy('kategori')->get();
        return view('price_rules.index', compact('kategori'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PriceRules  $priceRules
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $priceRules = PriceRules::find($id);
        $priceRules->delete();

        return response(null, 204);
    }
}
