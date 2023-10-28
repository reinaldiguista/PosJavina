<?php

namespace App\Http\Controllers;

use App\Models\JobApi;
use App\Models\ListProductTransaction;
use App\Models\Pembelian;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class JobApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('job_api.index');
    }

    
    public function data()
    {
        $job = JobApi::where('status', 0)->get();

        // return response()->json('Data dikirim ke kasir', 400);
        
        return datatables()
        ->of($job)
        ->addIndexColumn()
        ->addColumn('sku', function ($job) {
                return '<span class="label label-success">'. $job->sku .'</span>';
            })
        ->addColumn('count', function ($job) {
                return $job->count;
            })
            ->addColumn('action', function ($job) {
                return $job->action ;
            })
            ->addColumn('status', function ($job) {
                $y = $job->status;
                if ($y > 0) {
                    return "Sinkron" ;
                } else {
                    return "Belum Sinkron" ;
                }
            })
            ->addColumn('created', function ($job) {
                return tanggal_indonesia($job->created_at);
            })
            ->addColumn('updated', function ($job) {
                return tanggal_indonesia($job->updated_at);
            })
            ->addColumn('aksi', function ($job) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="sync(`'. route('job_api.sync', $job->sku ) .'`)" class="btn btn btn-warning "><i class="fa fa-handshake-o"></i> Sync</button>
                </div>
                ';
            })
            ->rawColumns(['sku', 'aksi'])
            ->make(true);
    }
    
    public function sync($sku)
    {
        $produk = Produk::where('sku', $sku)->first();

        $job = JobApi::where('sku', $sku)->where('status', 0)->first();
        $action = $job->action;
        $stock = $job->count;
        try {
            $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                'username' => 'isitaman',
                'password' => '1s1t4m4nJ@v1n4.',
                'action' => $action,
                'stock' => $stock,
                'sku' => $sku,
            ])['data'];

            $data  = [
                'sku' => $response['sku'],
                'stock' => $response['stock'],
                'status' => "sukses"
            ];
    
            $produk->stock = $response['stock'];
            $produk->isSync = 1;
            $produk->update();

            $job->status = 1;
            $job->update();

            return response($data);

        } catch (\Throwable $th) {
            $data  = [
                'sku' => $response['sku'],
                'stock' => $response['stock'],
                'status' => "Data produk tidak ditemukan"
            ];

            return response($data);
        }
    }

    public function sync_all()
    {
        $pembelian = Pembelian::where('order_type', 0)->get();

        foreach ($pembelian as $item) {

            $item->delete();
        }

        // $jobs = JobApi::where('status', 0)->orderBy('created_at', 'asc')->get();
        // $transaction = Pembelian::orderBy('created_at', 'asc')->get();

        // foreach ($transaction as $key ) {
            
        //     $lsp = ListProductTransaction::where('transaction_id', $key->id)->get();

        //     foreach ($lsp as $value) {
        //         $value->created_at = $key->created_at;
        //         $value->update();
        //     }
        // }

        $jobs = JobApi::where('status', 0)->orderBy('created_at', 'asc')->get();

        foreach ($jobs as $job) {
            $produk = Produk::where('sku', $job->sku)->first();

            $action = $job->action;
            $stock = $job->count;
            $sku = $job->sku;


            if ($action == "replace") {
                try {
                    $response = Http::put('https://pos.isitaman.com/product/replaceStockBySku', [
                        'username' => 'isitaman',
                        'password' => '1s1t4m4nJ@v1n4.',
                        'stock' => $stock,
                        'sku' => $sku,
                    ])['data'];
                    
                    $data  = [
                        'sku' => $response['sku'],
                        'stock' => $response['stock']
                    ];
            
                    // $produk->stock = $response['stock'];
                    // $produk->stock_isitaman = $response['stock'];
                    // $produk->isSync = 1;
                    // $produk->update();
                    $produk->stock = $response['stock'];
                    $produk->isSync = 1;
                    $produk->update();

                    $job->status = 1;
                    $job->update();
    
                } catch (\Throwable $th) {

                }
            } else {
                try {
                    $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                        'username' => 'isitaman',
                        'password' => '1s1t4m4nJ@v1n4.',
                        'action' => $action,
                        'stock' => $stock,
                        'sku' => $sku,
                    ])['data'];
        
                    $data  = [
                        'sku' => $response['sku'],
                        'stock' => $response['stock'],
                        'status' => "sukses"
                    ];
            
                    $produk->stock = $response['stock'];
                    $produk->isSync = 1;
                    $produk->update();
        
                    $job->status = 1;
                    $job->update();
            
                } catch (\Throwable $th) {
                    $data  = [
                        'sku' => $sku,
                        'stock' => $stock,
                        'status' => "Data produk gagal sinkron"
                    ];
                }
            }
            
                    
        }

        
        return response($data);

    }

    public function cek_sync()
    {

        $jobs = JobApi::where('status', 0)->get();

        $x = count($jobs);
        if ($x == 0){
            $data  = [
                'status' => 'success',
            ];
        } else {
            
            $data  = [
                'status' => 'fail',
            ];
        }
        return response($data);
    }

    public function local_product()
    {
        return view('job_api.local_product');
    }

    public function isitaman_data()
    {
        $produk = Produk::orderBy('sku', 'asc')->where('isLocal' , 0)->get();
        
        return datatables()
        ->of($produk)
        ->addIndexColumn()

        ->addColumn('sku_it', function ($produk) {
            return '<span class="label label-success">'. $produk->sku .'</span>';
        })
        ->addColumn('title_it', function ($produk) {
            return $produk->title ;
        })
        ->addColumn('stock_it', function ($produk) {
            return format_uang($produk->stock);
        })   
        ->addColumn('stock_isitaman_it', function ($produk) {
            return format_uang($produk->stock_isitaman);    
        })
        ->addColumn('isLocal_it', function ($produk) {
            return '
                    <i  class="fa fa-check-circle text-success"></i>
                    ';
        })
        ->addColumn('aksi_it', function ($produk) {
            return '
            <div>
                <button type="button" onclick="stock(`'. route('job_api.stock', $produk->sku) .'`)" class="btn btn-xs btn-warning "><i class="fa fa-cubes"></i> Update</button>
                <button type="button" onclick="sync(`'. route('job_api.sync_each', $produk->sku) .'`)" class="btn btn-xs btn-success "><i class="fa fa-handshake-o"></i> sync stock</button>
            </div>
            ';
        })
        ->rawColumns(['sku_it','aksi_it','isLocal_it'])
        ->make(true);
    }
    
    public function local_data()
    {
        
        $produk = Produk::orderBy('sku', 'asc')->where('isLocal' , 1)->get();
        // $produk = Produk::orderBy('sku', 'asc')->where('id','>', 500)->where('id','<', 1001)->get();

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('sku', function ($produk) {
                return '<span class="label label-success">'. $produk->sku .'</span>';
            })
            ->addColumn('title', function ($produk) {
                return $produk->id ;
            })
            ->addColumn('stock', function ($produk) {
                return format_uang($produk->stock);
            })   
            ->addColumn('stock_isitaman', function ($produk) {
                return format_uang($produk->stock_isitaman);    
            })
            ->addColumn('isLocal', function ($produk) {
                return '
                    <i  class="fa fa-times-circle text-danger"></i>
                    ';
            })
            ->addColumn('aksi', function ($produk) {
                return '
                <div>
                    <button type="button" onclick="stock(`'. route('job_api.stock', $produk->sku) .'`)" class="btn btn-xs btn-warning "><i class="fa fa-cubes"></i> Update</button>
                    <button type="button" onclick="sync(`'. route('job_api.sync_each', $produk->sku) .'`)" class="btn btn-xs btn-success "><i class="fa fa-handshake-o"></i> sync stock</button>
                </div>
                ';
            })

            ->rawColumns(['sku', 'isLocal', 'aksi'])
            ->make(true);
    }

    public function stock($sku)
    {
    $produk = Produk::where('sku', $sku)->first();

    if ($produk->isLocal == 1) { //lokal
        // cek data ke isiTaman
            // kalau ada :
            // ambil stok simpan ke stockIsiTaman
            // sukses
            // kalau gagal isi tabel job di alert
            // stok_isitaman = 0
        // kalau tidak ada :
            // alert gagal produk belum diupload
            // stok_isitaman = 0
            try {
                $response =  Http::get('https://pos.isitaman.com/product/getBySku?username=isitaman&password=1s1t4m4nJ@v1n4.&sku='. $sku )['data'];
            
                    if (!empty($response)) {            
                                
                        $data  = [
                            'sku' => $sku,
                            'stock' => $response['stock'],
                            'status' => "sukses_cek",
                            
                        ];
                        $produk->stock_isitaman = $response['stock'];
                        $produk->isSync = 1;
                        $produk->isLocal = 0;
                        $produk->update();
            
                    // var_dump($response);

                    } else {
                    
                    $produk->isSync = 0;
                    $produk->isLocal = 1;
                    $produk->update();
                        
                    $data  = [
                        'sku' => $sku,
                        'stock' => $response['stock'],
                        'status' => "fail_connect"
                    ];    
                    
                    } 

            } catch (\Throwable $th) {
                
                $produk->isSync = 0;
                $produk->isLocal = 1;
                $produk->update();

                $data  = [
                    'sku' => $sku,
                    'stock' => $produk->stock,
                    'status' => 'fail_produk'
                ]; 
                    
            }

    } else { //produk sudah upload
        // cek isi tabel job
            // kalau ada di job , alert ke halaman job dulu
        // kalau aman cek api
            // ambil data stok
                // kalau gagal isi tabel job
            // isi ke row stock_isitaman
            // kalau sukses okee
        $job = JobApi::where('sku', $sku)->where('status', 0)->first();    
        
        if ($job) {

            $produk->isSync = 0;
            $produk->isLocal = 1;
            $produk->update();
            
            $data  = [
                'sku' => $sku,
                'status' => "not_sync",
            ];
            
        } else {

            try {
                $response =  Http::get('https://pos.isitaman.com/product/getBySku?username=isitaman&password=1s1t4m4nJ@v1n4.&sku='. $produk->sku )['data'];
            
                if (!empty($response)) {            
                        
                        $data  = [
                            'sku' => $produk->sku,
                            'stock' => $response['stock'],
                            'status' => "sukses_update",
                        ];
                        // $produk->stock = $response['stock'];
                        $produk->stock_isitaman = $response['stock'];
                        $produk->isSync = 1;
                        $produk->isLocal = 0;
                        $produk->update();
            
                    // var_dump($response);
                } else {
                    
                    $produk->isSync = 0;
                    $produk->isLocal = 1;
                    $produk->update();

                    $data  = [
                        'sku' => $produk->sku,
                        'stock' => $response['stock'],
                        'status' => "fail_produk"
                    ];
                }            
            } 
            catch (\Throwable $th) {
                
                $produk->isSync = 0;
                $produk->isLocal = 1;
                $produk->update();

                $data  = [
                    'sku' => $produk->sku,
                    'stock' => $produk->stock,
                    'status' => 'fail_connect'
                ]; 
            }        
        }
    }
    return response($data);
}

    public function check_all()
    {
       $produk = Produk::get();
    //    $produk = Produk::orderBy('sku', 'asc')->where('id','<', 500)->get();
    //    $produk = Produk::orderBy('sku', 'asc')->where('id','<', 1001)->get();
    //    $produk = Produk::orderBy('sku', 'asc')->where('id','>', 1000)->where('id','<', 2001)->get();
    //    $produk = Produk::orderBy('sku', 'asc')->where('id','>', 2000)->where('id','<', 3001)->get();
    //    $produk = Produk::orderBy('sku', 'asc')->where('id','>', 3000)->where('id','<', 4001)->get();
    //    $produk = Produk::orderBy('sku', 'asc')->where('id','>', 4000)->get();

       foreach ($produk as $item) {

        try {
            $response =  Http::get('https://pos.isitaman.com/product/getBySku?username=isitaman&password=1s1t4m4nJ@v1n4.&sku='. $item->sku )['data'];
        
            if (!empty($response)) {            
                        
                $data  = [
                    'sku' => $item->sku,
                    'stock' => $response['stock'],
                    'status' => "sukses",
                ];
                $item->stock_isitaman = $response['stock'];
                $item->isSync = 1;
                $item->isLocal = 0;
                $item->update();
    
            // var_dump($response);
        } else {
            $item->isSync = 0;
            $item->isLocal = 1;
            $item->update();
            
            $data  = [
                'sku' => $item->sku,
                'stock' => $response['stock'],
                'status' => "fail_connect"
            ];

            
        } 
        } catch (\Throwable $th) {
            $item->isSync = 0;
            $item->isLocal = 1;
            $item->update();
            
            $data  = [
                'sku' => $item->sku,
                'stock' => $item->stock,
                'status' => 'fail_produk'
            ]; 
            // var_dump($data);        
        }
    }

    return response('selesai');

}
    
    
    public function sync_stock()
    {
        $produk = Produk::where('isLocal', 0)->where('isSync', 1)->get();

        foreach ($produk as $item) {
            $job = JobApi::where('sku', $item->sku)->first();
            
            $item->isSync = 0;
            $item->update();

                try {
                    $response =  Http::get('https://pos.isitaman.com/product/getBySku?username=isitaman&password=1s1t4m4nJ@v1n4.&sku='. $item->sku )['data'];
                
                    if (!empty($response)) {            
                            
                            $data  = [
                                'sku' => $item->sku,
                                'stock' => $response['stock'],
                                'status' => "sukses",
                                
                            ];
                            $item->stock = $response['stock'];
                            $item->isSync = 1;
                            $item->update();
                
                        // var_dump($response);
                    } else {
                        $data  = [
                            'sku' => $item->sku,
                            'stock' => $response['stock'],
                            'status' => "fail_connect"
                        ];
                        
                    }            
                } 
                catch (\Throwable $th) {
                    $data  = [
                        'sku' => $item->sku,
                        'stock' => $item->stock,
                        'status' => 'fail_produk'
                    ]; 
                            // $item->isSync = 0;
                            // $item->update();
                }   
        }
    }

    public function replace()
    {
        $produk = Produk::where('isLocal', 0)->where('isSync', 1)->get();

        foreach ($produk as $item) {
            $job = JobApi::where('sku', $item->sku)->first();
            
            $item->isSync = 0;
            $item->update();

            try {
                $response = Http::put('https://pos.isitaman.com/product/replaceStockBySku', [
                    'username' => 'isitaman',
                    'password' => '1s1t4m4nJ@v1n4.',
                    'stock' => $item->stock,
                    'sku' => $item->sku,
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
                $newJob->sku = $item->sku;
                $newJob->count = $item->stock;
                $newJob->action = 'replace';
                $newJob->endpoint = 'https://pos.isitaman.com/product/replaceStockBySku';
                $newJob->status = 0;
                $newJob->updated_at = Carbon::now();
                $newJob->save();
    
                $produk->isSync = 0;
                $produk->update();
                
                return response()->json('Gagal Mengirim data ke IsiTaman', 500);
            }   
        }
    }

    public function sync_each($sku)
    {
        $produk = Produk::where('sku', $sku)->first();
        if ($produk->isLocal == 1) {
            $data  = [
                'sku' => $sku,
                'status' => "not_upload",
            ];
        } else {
            $job = JobApi::where('sku', $sku)->where('status', 0)->first();    
            if ($job) {
                
                $produk->isSync = 0;
                $produk->isLocal = 1;
                $produk->update();

                $data  = [
                    'sku' => $sku,
                    'status' => "not_sync",
                ];

            } else {

                try {
                    $response =  Http::get('https://pos.isitaman.com/product/getBySku?username=isitaman&password=1s1t4m4nJ@v1n4.&sku='. $produk->sku )['data'];
                
                    if (!empty($response)) {            
                            
                            $data  = [
                                'sku' => $produk->sku,
                                'stock' => $response['stock'],
                                'status' => "sukses",
                                
                            ];
                            $produk->stock = $response['stock'];
                            $produk->stock_isitaman = $response['stock'];
                            $produk->isSync = 1;
                            $produk->isLocal = 0;
                            $produk->update();
                
                        // var_dump($response);
                    } else {
                    $produk->isSync = 0;
                    $produk->isLocal = 1;
                    $produk->update();
                        
                        $data  = [
                            'sku' => $produk->sku,
                            'stock' => $response['stock'],
                            'status' => "fail_produk"
                        ];
                    }            
                } 
                catch (\Throwable $th) {
                    $produk->isSync = 0;
                    $produk->isLocal = 1;
                    $produk->update();
                    
                    $data  = [
                        'sku' => $produk->sku,
                        'stock' => $produk->stock,
                        'status' => 'fail_connect'
                    ]; 
                }            
            }
        }   
        return response($data);
     
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
       
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }
}
