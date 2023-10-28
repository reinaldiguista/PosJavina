<?php

namespace App\Http\Controllers;

use App\Models\JobApi;
use App\Models\Produk;
use App\Models\Stock;
use App\Models\StockDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Svg\Gradient\Stop;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('stock.index');
    }

    public function data()
    {
        $stock = Stock::orderBy('created_at', 'desc')->get();
    
        // return response($stock);
        
        return datatables()
            ->of($stock)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($stock) {
                return tanggal_indonesia($stock->tanggal, false);
            })
            ->addColumn('tipe', function ($stock) {
                return $stock->tipe;
            })
            ->addColumn('name', function ($stock) {
                return $stock->name;
            })
            ->addColumn('total_barang', function ($stock) {
                return format_uang($stock->total_barang);
            })
            ->addColumn('asal', function ($stock) {
                return $stock->asal;
            })
            ->addColumn('lokasi_penerimaan', function ($stock) {
                return $stock->lokasi_penerimaan;
            })
            ->addColumn('pengirim', function ($stock) {
                return $stock->pengirim;
            })
            ->editColumn('penerima', function ($stock) {
                return $stock->penerima;
            })
            ->editColumn('catatan', function ($stock) {
                return $stock->catatan;
            })
            ->addColumn('status', function ($stock) {
                if ($stock->status == 1) {
                    return '<span class="label label-success">Konfirmasi</span>';
                } elseif ($stock->status == 2) {
                    return '<span class="label label-warning">Pending</span>';
                } else {
                    return '<span class="label label-danger">Cancel</span>';
                }                
            })
            ->addColumn('aksi', function ($stock) {
                if ($stock->status == 1) {
                    return '
                    <button style="margin-top: 5px" onclick="showDetail(`'. route('stock.show_detail', $stock->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-eye"> Detail</i></button>
                    <br>
                    <button style="margin-top: 5px" onclick="status(`'. route('stock.status', $stock->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"> Ubah Status</i></button>
                    ';
                } elseif ($stock->status == 2) {
                    return '
                    <button style="margin-top: 5px" onclick="edit(`'. route('stock.edit_stock', $stock->id) .'`)" class="btn btn-xs btn-primary "><i class="fa fa-cart-plus"> Edit Detail</i></button>
                    <br>
                    <a href="stock/add_stock/'. ($stock->id) .'" style="margin-top: 5px" class="btn btn-xs btn-success "><i class="fa fa-plus-square"> Masukkan Produk</i></a>    
                    <br>
                    <button style="margin-top: 5px" onclick="showDetail(`'. route('stock.show_detail', $stock->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-eye"> Detail</i></button>
                    <br>
                    <button style="margin-top: 5px" onclick="status(`'. route('stock.status', $stock->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"> Ubah Status</i></button>
                    ';                
                } else {
                    return '
                    <button onclick="showDetail(`'. route('stock.show_detail', $stock->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-eye"> Detail</i></button>
                    ';   
                }
            })
            ->rawColumns(['status' , 'aksi'])
            ->make(true);
    }

    public function add_stock($id)
    {
        // return response($id);
        return redirect()->route('stock_detail.index', $id);
    }

    public function store_stock(Request $request)
    {
        $id_stock = $request->id_stock;
        $stock = Stock::find($id_stock);
    
        $stock_detail = StockDetail::where('status', 0)->where('stock_id', $id_stock)->get();
        $total_item = 0;
        $x = "Stok Masuk";
        
        foreach ($stock_detail as $detail) {

            $product = Produk::find($detail->product_id);
            
            if ($product->isLocal == 1) {
                if ($stock->tipe == $x) {
                    $product->stock += $detail->count;
                    $product->update();
                } else {
                    $product->stock -= $detail->count;
                    $product->update();
                }    
            } else {
                
                if ($stock->tipe == $x) {
                    
                    try {
                        $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                            'username' => 'isitaman',
                            'password' => '1s1t4m4nJ@v1n4.',
                            'action' => 'increase',
                            'stock' => $detail->count,
                            'sku' => $product->sku,
                        ])['data'];
                        
                        $data  = [
                            'sku' => $response['sku'],
                            'stock' => $response['stock']
                        ];
                
                        $product->stock = $response['stock'];
                        $product->stock_isitaman = $response['stock'];
                        $product->isSync = 1;
                        $product->update();

                    } catch (\Throwable $th) {
                        $newJob = new JobApi();
                        $newJob->sku = $product->sku;
                        $newJob->count = $detail->count;
                        $newJob->action = 'increase';
                        $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                        $newJob->status = 0;
                        $newJob->updated_at = Carbon::now();
                        $newJob->save();
            
                        $product->stock += $detail->count;
                        $product->isSync = 0;
                        $product->update();
                        
                        $data  = [
                            'sku' => $product->sku,
                            'stock' => $product->stock,
                            'status' => "fail_connect"
                        ];
                    }
                
                
                } else {
                
                    try {
                        $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                            'username' => 'isitaman',
                            'password' => '1s1t4m4nJ@v1n4.',
                            'action' => 'decrease',
                            'stock' => $detail->count,
                            'sku' => $product->sku,
                        ])['data'];
                        
                        $data  = [
                            'sku' => $response['sku'],
                            'stock' => $response['stock'],
                            'status' => "sukses"
                        ];
                
                        $product->stock = $response['stock'];
                        $product->stock_isitaman = $response['stock'];
                        $product->isSync = 1;
                        $product->update();
                        
                    } catch (\Throwable $th) {
                        $newJob = new JobApi();
                        $newJob->sku = $product->sku;
                        $newJob->count = $detail->count;
                        $newJob->action = 'decrease';
                        $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                        $newJob->status = 0;
                        $newJob->updated_at = Carbon::now();
                        $newJob->save();
            
                        $product->isSync = 0;
                        $product->stock -= $detail->count;
                        $product->update();

                        $data  = [
                            'sku' => $product->sku,
                            'stock' => $product->stock,
                            'status' => "fail_connect"
                        ];
                    } 
                
                }
            }
            $total_item += $detail->count;
            $detail->status = 1;
            $detail->update();
        }
        
        // $stock->total_barang = $total_item;
        $stock->status = 1;
        $stock->update();

        return view('stock.index');
        // return redirect()->route('stock_detail.index', $id);
    }
    
    public function edit_stock(Request $request, $id)
    {
        $stock = Stock::find($id);
        $stock->update($request->all());

        return response($stock);
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
        // $stock = Stock::create($request->all());
        $stock = new Stock();
        $stock->tipe = $request->tipe;
        $stock->tanggal = $request->tanggal;
        
        if ($request->name_in) {
            if ($request->lainnya) {
                $stock->name = 'Lainnya,'. $request->lainnya;
            } else {
                $stock->name = $request->name_in;
            }
        } else {
            if ($request->lainnya) {
                $stock->name = 'Lainnya,'. $request->lainnya;
            } else {
                $stock->name = $request->name_out;
            }
        }

        $stock->pengirim = $request->pengirim;
        $stock->asal = $request->asal;
        $stock->penerima = $request->penerima;
        $stock->lokasi_penerimaan = $request->lokasi_penerimaan;
        $stock->catatan = $request->catatan;
        $stock->save();

        $stock_id = $stock->id;
        // return response($request);
        return view('stock.index');
        // return redirect()->route('stock_detail.index', $stock_id);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show_detail($id)
    {
        $detail = StockDetail::where('stock_id', $id)->get();
        
        
        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->sku .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->title;
            })
            // ->addColumn('stock_before', function ($detail) {
            //     return format_uang($detail->stock_before);
            // })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->count);
            })
            ->addColumn('catatan', function ($detail) {
                return $detail->catatan;
            })
            // ->addColumn('aksi', function ($detail) {
            //     return '
            //     <div>
            //         <button onclick="checkout(`'. $detail->customer_id . '`)" class="btn btn-xs btn-info "><i class="fa fa-eye"></i></button>
            //     </div>
            //     '; 
            // })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = Stock::find($id);
        $stock_detail = StockDetail::where('stock_id', $stock->id)->get();

        $x = "Stok Masuk";
        
        foreach ($stock_detail as $detail) {
            $product = Produk::find($detail->product_id);
            
            if ($product->isLocal == 1) {
                if ($stock->tipe == $x) {
                    $product->stock += $detail->count;
                    $product->update();
                } else {
                    $product->stock -= $detail->count;
                    $product->update();
                }
            } else {
                if ($stock->tipe == $x) {
                    try {
                        $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                            'username' => 'isitaman',
                            'password' => '1s1t4m4nJ@v1n4.',
                            'action' => 'decrease',
                            'stock' => $detail->count,
                            'sku' => $product->sku,
                        ])['data'];
                        
                        $data  = [
                            'sku' => $response['sku'],
                            'stock' => $response['stock'],
                            'status' => "sukses"
                        ];
                
                        $product->stock = $response['stock'];
                        $product->stock_isitaman = $response['stock'];
                        $product->isSync = 1;
                        $product->update();
                        
                    } catch (\Throwable $th) {
                        $newJob = new JobApi();
                        $newJob->sku = $product->sku;
                        $newJob->count = $detail->count;
                        $newJob->action = 'decrease';
                        $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                        $newJob->status = 0;
                        $newJob->updated_at = Carbon::now();
                        $newJob->save();
            
                        $product->isSync = 0;
                        $product->stock -= $detail->count;
                        $product->update();

                        $data  = [
                            'sku' => $product->sku,
                            'stock' => $product->stock,
                            'status' => "fail_connect"
                        ];
                    }
                
                } else {
                    try {
                        $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                            'username' => 'isitaman',
                            'password' => '1s1t4m4nJ@v1n4.',
                            'action' => 'increase',
                            'stock' => $detail->count,
                            'sku' => $product->sku,
                        ])['data'];
                        
                        $data  = [
                            'sku' => $response['sku'],
                            'stock' => $response['stock']
                        ];
                
                        $product->stock = $response['stock'];
                        $product->stock_isitaman = $response['stock'];
                        $product->isSync = 1;
                        $product->update();

                    } catch (\Throwable $th) {
                        $newJob = new JobApi();
                        $newJob->sku = $product->sku;
                        $newJob->count = $detail->count;
                        $newJob->action = 'increase';
                        $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                        $newJob->status = 0;
                        $newJob->updated_at = Carbon::now();
                        $newJob->save();
            
                        $product->stock += $detail->count;
                        $product->isSync = 0;
                        $product->update();
                        
                        $data  = [
                            'sku' => $product->sku,
                            'stock' => $product->stock,
                            'status' => "fail_connect"
                        ];
                    }
                }
            $detail->status = 2;
            $detail->update();
        }

        $stock->status = 0;
        $stock->update();

        return response(null, 204);
        }
    }
}