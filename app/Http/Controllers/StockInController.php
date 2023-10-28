<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('stock_in.index');
    }

    public function data()
    {
        $stock_in = StockIn::orderBy('created_at', 'desc')->get();
    
        return datatables()
            ->of($stock_in)
            ->addIndexColumn()
            ->rawColumns(['aksi'])
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockIn  $stockIn
     * @return \Illuminate\Http\Response
     */
    public function show(StockIn $stockIn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockIn  $stockIn
     * @return \Illuminate\Http\Response
     */
    public function edit(StockIn $stockIn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockIn  $stockIn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockIn $stockIn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockIn  $stockIn
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockIn $stockIn)
    {
        //
    }
}
