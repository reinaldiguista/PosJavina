<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\JobApi;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use PDF;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');


        return view('produk.index');

    }

    public function only_produk()
    {
        // $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');

        return view('produk.index_view');
    }

    public function data()
    {
        // $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')
        //     ->select('produk.*', 'nama_kategori')
        //     // ->orderBy('kode_produk', 'asc')
        //     ->get();

        $produk = Produk::get();

        return datatables()
            ->of($produk)
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-success">'. $produk->sku .'</span>';
            })
            ->addColumn('title', function ($produk) {
                return $produk->title ;
            })
            ->addColumn('stock', function ($produk) {
                return format_uang($produk->stock);
            })   
            ->addColumn('price', function ($produk) {
                return format_uang($produk->price);
            })
            ->addColumn('offline_price', function ($produk) {
                return format_uang($produk->offline_price);
            })
            ->addColumn('agen_price', function ($produk) {
                return format_uang($produk->agen_price);
            })
            ->addColumn('reseller_price', function ($produk) {
                return format_uang($produk->reseller_price);
            })
            ->addColumn('nomor_meja', function ($produk) {
                return $produk->nomor_meja ;
            })
            ->addColumn('isSync', function ($produk) {
                if ($produk->isSync == 1) {
                    return 'Sync';
                } else {
                    return 'not_sync';
                }
            })
            ->addColumn('aksi', function ($produk) {
                return '
                <div>
                    <button type="button" onclick="cekStok(`'. route('produk.sku', $produk->sku) .'`)" class="btn btn-xs btn-warning "><i class="fa fa-cubes"></i>Cek Stok</button>
                    <button type="button" onclick="showDetail(`'. route('produk.show', $produk->id) .'`)" class="btn btn-xs btn-success "><i class="fa fa-eye"></i>Detail Produk</button>
                    <button type="button" onclick="editForm(`'. route('produk.update', $produk->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-pencil"></i>Edit Produk</button>
                    <button type="button" onclick="deleteData(`'. route('produk.destroy', $produk->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i>Hapus Produk</button>
                </div>
                ';
            })
            ->addColumn('cek_stok', function ($produk) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="cekStok(`'. route('produk.sku', $produk->sku) .'`)" class="btn btn-xs btn-warning "><i class="fa fa-cubes"></i>Cek Stok</button>
                    <button type="button" onclick="showDetail(`'. route('produk.show', $produk->id) .'`)" class="btn btn-xs btn-success "><i class="fa fa-eye"></i>Detail Produk</button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk', 'cek_stok'])
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
        // $produk = Produk::create($request->all());
        // $produk->sku = "sku";
        // $produk->stock = 10;
        // $produk->update();

        // $produk = new Produk();
        // $produk->title = $request->title;
        // $produk->sku = "sku";
        // $produk->price = $request->customer_type;
        // $produk->save();


        return response($request);
        // return view('produk.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Produk::find($id);

        return response()->json($produk);
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);
        $produk->update($request->all());

        return view('produk.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }

        return response(null, 204);
    }

    public function sku($sku)
    {
    $produk = Produk::where('sku', $sku)->first();
    
    if ($produk->isLocal == 1) {
        
        $data  = [
            'sku' => $sku,
            'stock' => $produk->stock,
            'status' => "local_product"
        ];
        
        return response($data);

    } else {
        $job = JobApi::where('sku', $sku)->where('status' , 0)->first();
    
        if (!empty($job)) {
            $produk->isSync = 0;
            $produk->update();
            
            $data  = [
                'sku' => $sku,
                'stock' => $produk->stock,
                'status' => "fail_job"
            ];
            return response($data);

        } else {
            
            $produk->isSync = 0;
            $produk->update();
        
            try {
                $response =  Http::get('https://pos.isitaman.com/product/getBySku?username=isitaman&password=1s1t4m4nJ@v1n4.&sku='. $sku )['data'];
            
                if (!empty($response)) {            
                        
                        $data  = [
                            'sku' => $sku,
                            'stock' => $response['stock'],
                            'status' => "sukses",
                            
                        ];
                        $produk->stock = $response['stock'];
                        $produk->stock_isitaman = $response['stock'];
                        $produk->isSync = 1;
                        $produk->update();
            
                    // var_dump($response);
                    return response($data);
                } else {
                    $data  = [
                        'sku' => $sku,
                        'stock' => $response['stock'],
                        'status' => "fail_connect"
                    ];
                    
                    return response($data);
                }            
            } 
            catch (\Throwable $th) {
                $data  = [
                    'sku' => $sku,
                    'stock' => $produk->stock,
                    'status' => 'fail_produk'
                ]; 
                        $produk->isSync = 0;
                        $produk->update();

                return response($data);
                // var_dump($data);
            }
        }
    }       
}


    //kirim stok ke isitaman
    public function send_stock($sku, $count)
    {    
    $produk = Produk::where('sku', $sku)->first();
    
    if ($produk->isLocal == 1) {
        
        $produk->stock += $count;
        $produk->update();

        $data  = [
            'sku' => $sku,
            'stock' => $produk->stock,
            'status' => "local_product"
        ];
        
        return response($data);    

    } else {

        $job = JobApi::where('sku', $sku)->where('status' , 0)->first();
    
        if (!empty($job)) {
            
            $produk->isSync = 0;
            $produk->stock += $count;
            $produk->update();
            
            $newJob = new JobApi();
                $newJob->sku = $sku;
                $newJob->count = $count;
                $newJob->action = 'increase';
                $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                $newJob->status = 0;
                $newJob->updated_at = Carbon::now();
                $newJob->save();
    
            $data  = [
                'sku' => $sku,
                'stock' => $produk->stock,
                'message' => "stok berhasil ditambah, produk masih belum sinkron",
                'status' => "fail_job"
            ];
    
            return response($data);
            // return redirect()->route('job_api.index');
        
        } else {
            try {
                $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                    'username' => 'isitaman',
                    'password' => '1s1t4m4nJ@v1n4.',
                    'action' => 'increase',
                    'stock' => $count,
                    'sku' => $sku,
                ])['data'];
                
                $data  = [
                    'sku' => $response['sku'],
                    'stock' => $response['stock']
                ];
        
                $produk->stock = $response['stock'];
                $produk->stock_isitaman = $response['stock'];
                $produk->update();
            } catch (\Throwable $th) {
                $newJob = new JobApi();
                $newJob->sku = $sku;
                $newJob->count = $count;
                $newJob->action = 'increase';
                $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                $newJob->status = 0;
                $newJob->updated_at = Carbon::now();
                $newJob->save();
    
                $produk->isSync = 0;
                $produk->update();
                
                return response()->json('Gagal Mengirim data ke IsiTaman', 500);
            }       
        }    
    }    
}

    //ambil stok dari isitaman
    public function remove_stock($sku, $count)
    {    
        $produk = Produk::where('sku', $sku)->first();
        
        $job = JobApi::where('sku', $sku)->where('status' , 0)->first();
        
        if ($produk->stock < $count) {
            
            $data  = [
                'status' => 'fail_stok',
                'message' => 'Jumlah Melebihi stok'
            ];

            return response($data);
        } else {
            
            if ($produk->isLocal == 1) {
                $produk->stock -= $count;
                $produk->update();
        
                $data  = [
                    'sku' => $sku,
                    'stock' => $produk->stock,
                    'status' => "local_product"
                ];
                
                return response($data);
    
            } else {
                if (!empty($job)) {
            
                    $produk->isSync = 0;
                    $produk->stock -= $count;
                    $produk->update();
    
                    $newJob = new JobApi();
                    $newJob->sku = $sku;
                    $newJob->count = $count;
                    $newJob->action = 'decrease';
                    $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                    $newJob->status = 0;
                    $newJob->updated_at = Carbon::now();
                    $newJob->save();
        
                $data  = [
                    'sku' => $sku,
                    'stock' => $produk->stock,
                    'message' => "stok lokal berhasil berkurang",
                    'status' => "fail_job"
                ];
                        
                } else {
                    try {
                        $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                            'username' => 'isitaman',
                            'password' => '1s1t4m4nJ@v1n4.',
                            'action' => 'decrease',
                            'stock' => $count,
                            'sku' => $sku,
                        ])['data'];
                        
                        $data  = [
                            'sku' => $response['sku'],
                            'stock' => $response['stock'],
                            'status' => "sukses"
                        ];
                
                        $produk->stock = $response['stock'];
                        $produk->stock_isitaman = $response['stock'];
                        $produk->update();
                        
                    } catch (\Throwable $th) {
                        $newJob = new JobApi();
                        $newJob->sku = $sku;
                        $newJob->count = $count;
                        $newJob->action = 'decrease';
                        $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                        $newJob->status = 0;
                        $newJob->updated_at = Carbon::now();
                        $newJob->save();
            
                        $produk->isSync = 0;
                        $produk->stock -= $count;
                        $produk->update();

                        $data  = [
                            'sku' => $sku,
                            'stock' => $produk->stock,
                            'status' => "fail_connect"
                        ];
                    }       
                }            
            }
        }
        return response($data);

    }
    

    // public function cetakBarcode(Request $request)
    // {
    //     $dataproduk = array();
    //     foreach ($request->id as $id) {
    //         $produk = Produk::find($id);
    //         $dataproduk[] = $produk;
    //     }

    //     $no  = 1;
    //     $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
    //     $pdf->setPaper('a4', 'potrait');
    //     return $pdf->stream('produk.pdf');
    // }
}
