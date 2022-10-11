<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Cart;
use App\Models\JobApi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use PDF;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = Member::distinct('customer_type')->get();

        return view('cart.index', compact('member'));
    }

    public function data()
    {
        $carts = Cart::with('member')
                ->select('customer_id', 'employee_id', 'isSend')
                ->where('flag', 0)
                ->distinct('customer_id')
                ->get();
        
        return datatables()
            ->of($carts)
            ->addIndexColumn()
            ->addColumn('select_all', function ($carts) {
                return '
                    <input type="checkbox" name="id[]" value="'. $carts->id .'">
                ';
            })
            ->addColumn('customer', function ($carts) {
                return $carts->member->name;
            })
            ->addColumn('employee', function ($carts) {
                return $carts->user->name;
            })
            ->addColumn('isSend', function ($carts) {
                if ($carts->isSend == 1) {
                    return "Terkirim ke Kasir";
                } else {
                    return "Delay";
                }
            })
            ->addColumn('aksi', function ($carts) {
                
                if ($carts->isSend == 1) {
                    return '
                    <div class="btn-group">
                        <button onclick="showDetail(`'. route('cart.show_send', $carts->customer_id) .'`)" class="btn btn-sm btn-info btn-block"><i class="fa fa-eye"> Detail</i></button>    
                        <button onclick="deleteData(`'. route('cart.purge_send', $carts->customer_id) .'`)" class="btn btn-sm btn-danger btn-block"><i class="fa fa-trash"> Hapus</i></button>
                    </div>
                    ';                
                } else {
                    return '
                    <div class="btn-group">
                        <a href="cart/'. ($carts->customer_id) .'/creates" class="btn btn-sm btn-warning btn-block"><i class="fa fa-plus-square"> Edit</i></a>    
                        <button onclick="sendCart(`'. ($carts->customer_id) .'`)" class="btn btn-sm btn-success btn-block"><i class="fa fa-shopping-basket"> Kasir</i></button>    
                        <button onclick="showDetail(`'. route('cart.show_cart', $carts->customer_id) .'`)" class="btn btn-sm btn-info btn-block"><i class="fa fa-eye"> Detail</i></button>    
                        <button onclick="deleteData(`'. route('cart.purge_cart', $carts->customer_id) .'`)" class="btn btn-sm btn-danger btn-block"><i class="fa fa-trash"> Hapus</i></button>
                    </div>
                    ';
                }                
            })
            ->rawColumns(['aksi', 'select_all'])
            ->make(true);
    }


    /**                    

     *                     

     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create($id){
      
        $id_customer = $id;
        $produk = Produk::where('stock', '!=' , 0)->get();
        $customer = Member::where('id', $id) ->first();
        session(['id_customer' => $id]);
        
        return view('cart.create', compact('customer', 'produk', 'id_customer'));
    }

    
    public function checkout($id){
      
        $member = Member::distinct('customer_type')->get();
        $cart = Cart::with('member')
        ->select('customer_id', 'employee_id')
        ->where('isSend', 1)
        ->where('flag', 0)
        ->distinct()
        ->get();

        return redirect()->route('pembelian.index', compact('member','cart'));

    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($customer_id)
    {

        $cart = Cart::where('customer_id', $customer_id)->get();
        foreach ($cart as $cart) {
            $cart->isSend = 1;
            $cart->update();
        }

        $member = Member::distinct('customer_type')->get();

        return view('cart.index', compact('member'));

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show_send($id)
    {
        $detail = Cart::where('customer_id', $id)->where('isSend', 1)->where('flag', 0)->get();
        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_id', function ($detail) {
                return $detail->produk->title ;
            })
            ->addColumn('base_price', function ($detail) {
                return 'Rp. '. format_uang($detail->base_price);
            })
            ->addColumn('count', function ($detail) {
                return $detail->count;
            })
            ->addColumn('final_price', function ($detail) {
                // $x = $detail->base_price;
                // $y = $detail->count;
                // $z = $x * $y;
                // $detail->final_price = $z;
                return 'Rp. '. format_uang($detail->final_price);
            })
            ->addColumn('special_case', function ($detail) {
                if ($detail->isSpecialCase == 1) {
                    return "spesial case";
                } else {
                    return "normal";
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_cart($id)
    {
        $detail = Cart::where('customer_id', $id)->where('isSend', 0)->where('flag', 0)->get();
        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_id', function ($detail) {
                return $detail->produk->title ;
            })
            ->addColumn('base_price', function ($detail) {
                return 'Rp. '. format_uang($detail->base_price);
            })
            ->addColumn('count', function ($detail) {
                return $detail->count;
            })
            ->addColumn('final_price', function ($detail) {
                // $x = $detail->base_price;
                // $y = $detail->count;
                // $z = $x * $y;
                // $detail->final_price = $z;
                return 'Rp. '. format_uang($detail->final_price);
            })
            ->addColumn('special_case', function ($detail) {
                if ($detail->isSpecialCase == 1) {
                    return "spesial case";
                } else {
                    return "normal";
                }                
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = $request->id;
        $count = $request->jumlah; //req
        
        $detail = Cart::find($id); //count
        $produk = Produk::find($detail->product_id);

        if ($count == 0) {
            $produk->stock += $detail->count;
            $produk->update();
            
            $detail->delete();
            return response()->json('Terhapus', 200);
        } else {

         
        
        
        
        $detail->final_price = $detail->base_price * $detail->count;
        
        $stok = $produk->stock; //stok
        $sku = $produk->sku;
        
        $condition = $detail->count - $count;

        if ($condition < 0) { // stok berkurang 
            $x = $count - $detail->count;
            $y = $stok - $x;

            if ($y >= 0) {
                $detail->count = $count;
                $detail->final_price = $detail->base_price * $count;
                $detail->update();

                if ($produk->isLocal == 1) {
                    $produk->stock -= $x;
                    $produk->update();

                    $data  = [
                        'sku' => $sku,
                        'stock' => $produk->stock,
                        'status' => "local_product_remove"
                    ];

                } else {
                    $job = JobApi::where('sku', $sku)->where('status' , 0)->first();

                    if (!empty($job)) {
                        
                        $newJob = new JobApi();
                            $newJob->sku = $sku;
                            $newJob->count = $x;
                            $newJob->action = 'decrease';
                            $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                            $newJob->status = 0;
                            $newJob->updated_at = Carbon::now();
                            $newJob->save();
                
                            $produk->isSync = 0;
                            $produk->stock -= $x;
                            $produk->update();
                            
                            $data  = [
                                'sku' => $sku,
                                'stock' => $produk->stock,
                                'status' => "fail_job_remove"
                            ];
                    
                    } else {
                        
                        $produk->isSync = 0;
                        $produk->update();

                        try {
                            $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                                'username' => 'isitaman',
                                'password' => '1s1t4m4nJ@v1n4.',
                                'action' => 'decrease',
                                'stock' => $x,
                                'sku' => $sku,
                            ])['data'];
                            
                            $data  = [
                                'sku' => $response['sku'],
                                'stock' => $response['stock'],
                                'status' => "sukses_remove"
                            ];
                    
                            $produk->stock = $response['stock'];
                            $produk->stock_isitaman = $response['stock'];
                            $produk->isSync = 1;
                            $produk->update();
                            
                        } catch (\Throwable $th) {
                            $newJob = new JobApi();
                            $newJob->sku = $sku;
                            $newJob->count = $x;
                            $newJob->action = 'decrease';
                            $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                            $newJob->status = 0;
                            $newJob->updated_at = Carbon::now();
                            $newJob->save();
                
                            $produk->isSync = 0;
                            $produk->stock -= $x;
                            $produk->update();
                            
                            $data  = [
                                'sku' => $sku,
                                'stock' => $produk->stock,
                                'status' => "fail_connect_remove"
                            ];
                        }       
                    }
                }                
            } else {
                return response('Jumlah Stok tidak memenuhi', 400);
            }
            
        } else if ($condition > 0) { //stok bertambah
            $x = $detail->count - $count;
            $detail->count = $count;
            $detail->final_price = $detail->base_price * $count;            
            $detail->update();
            
            if ($produk->isLocal == 1) {
                $produk->stock += $x;
                $produk->update();

                $data  = [
                    'sku' => $sku,
                    'stock' => $produk->stock,
                    'status' => "local_product_add"
                ];

            } else {
                $job = JobApi::where('sku', $sku)->where('status' , 0)->first();
                        if (!empty($job)) {
                            $newJob = new JobApi();
                            $newJob->sku = $sku;
                            $newJob->count = $x;
                            $newJob->action = 'increase';
                            $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                            $newJob->status = 0;
                            $newJob->updated_at = Carbon::now();
                            $newJob->save();

                            $produk->isSync = 0;
                            $produk->stock += $x;
                            $produk->update();
                                        
                            $data  = [
                                'sku' => $sku,
                                'stock' => $produk->stock,
                                'status' => "fail_job_add"
                            ];

                        } else {
                            try {
                                       $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                                            'username' => 'isitaman',
                                            'password' => '1s1t4m4nJ@v1n4.',
                                            'action' => 'increase',
                                            'stock' => $x,
                                            'sku' => $sku,
                                        ])['data'];
                                        
                                        $data  = [
                                            'sku' => $response['sku'],
                                            'stock' => $response['stock'],
                                            'status' => "sukses_add"
                                        ];

                                        $produk->isSync = 1;
                                        $produk->stock = $response['stock'];
                                        $produk->stock_isitaman = $response['stock'];
                                        $produk->update();

                                    } catch (\Throwable $th) {
                                        $newJob = new JobApi();
                                        $newJob->sku = $sku;
                                        $newJob->count = $x;
                                        $newJob->action = 'increase';
                                        $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                                        $newJob->status = 0;
                                        $newJob->updated_at = Carbon::now();
                                        $newJob->save();

                                        $produk->isSync = 0;
                                        $produk->stock += $x;
                                        $produk->update();
                                        
                                        $data  = [
                                            'sku' => $sku,
                                            'stock' => $produk->stock,
                                            'status' => "fail_connect_add"
                                        ];
                                    }       
                                }
            }

        } else {
            $x = $detail->count;
            $detail->count = $count;
            $detail->final_price = $detail->base_price * $count;            
            $detail->update();
            
            if ($produk->isLocal == 1) {
                $produk->stock += $x;
                $produk->update();

                $detail->delete();
                
                $data  = [
                    'sku' => $sku,
                    'stock' => $produk->stock,
                    'status' => "local_product_delete"
                ];

            } else {
                $job = JobApi::where('sku', $sku)->where('status' , 0)->first();
                        if (!empty($job)) {
                            $newJob = new JobApi();
                            $newJob->sku = $sku;
                            $newJob->count = $x;
                            $newJob->action = 'increase';
                            $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                            $newJob->status = 0;
                            $newJob->updated_at = Carbon::now();
                            $newJob->save();

                            $produk->isSync = 0;
                            $produk->stock += $x;
                            $produk->update();
                               
                            $detail->delete();

                            $data  = [
                                'sku' => $sku,
                                'stock' => $produk->stock,
                                'status' => "fail_job_delete"
                            ];

                        } else {
                            try {
                                       $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                                            'username' => 'isitaman',
                                            'password' => '1s1t4m4nJ@v1n4.',
                                            'action' => 'increase',
                                            'stock' => $x,
                                            'sku' => $sku,
                                        ])['data'];
                                        
                                        $data  = [
                                            'sku' => $response['sku'],
                                            'stock' => $response['stock'],
                                            'status' => "sukses"
                                        ];

                                        $produk->isSync = 1;
                                        $produk->stock = $response['stock'];
                                        $produk->stock_isitaman = $response['stock'];
                                        $produk->update();

                                        $detail->delete();

                                    } catch (\Throwable $th) {
                                        $newJob = new JobApi();
                                        $newJob->sku = $sku;
                                        $newJob->count = $x;
                                        $newJob->action = 'increase';
                                        $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                                        $newJob->status = 0;
                                        $newJob->updated_at = Carbon::now();
                                        $newJob->save();

                                        $produk->isSync = 0;
                                        $produk->stock += $x;
                                        $produk->update();

                                        $detail->delete();
                                        
                                        $data  = [
                                            'sku' => $sku,
                                            'stock' => $produk->stock,
                                            'status' => "fail_connect_delete"
                                        ];
                                    }       
                                }
                            }
        }

        return response($data);

        }
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    $cart = Cart::find($id);
    $count = $cart->count;  
    $produk = Produk::find($cart->product_id);
    $sku = $produk->sku;

    //function send_stock
    $produk = Produk::where('sku', $sku)->first();
    
    if ($produk->isLocal == 1) {
        $produk->stock += $count;
        $produk->update();

        $data  = [
            'sku' => $sku,
            'stock' => $produk->stock,
            'status' => "local_product"
        ];
        
        $cart->delete();

        return response($data);

    } else {
        
        $job = JobApi::where('sku', $sku)->where('status' , 0)->first();

        if (!empty($job)) {
        
            $newJob = new JobApi();
                $newJob->sku = $sku;
                $newJob->count = $count;
                $newJob->action = 'increase';
                $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                $newJob->status = 0;
                $newJob->updated_at = Carbon::now();
                $newJob->save();
    
                $produk->isSync = 0;
                $produk->stock += $count;
                $produk->update();
    
                $cart->delete();
            
            $data  = [
                'status' => 'masih_di_job'
            ];
            return response($data);
        
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
                    'stock' => $response['stock'],
                    'status' => 'sukses'
                ];
        
                $produk->stock = $response['stock'];
                $produk->stock_isitaman = $response['stock'];
                $produk->update();

                $cart->delete();
                return response($data);

                
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
                $produk->stock += $count;
                $produk->update();
    
                $cart->delete();
    
                $data  = [
                    'status' => 'fail_send',
                    'stock' => $response['stock']
                ];
                
                return response($data);
            }       
        }        
    }
    
    
    
    }

    public function addProduct($sku, $count, $customer_id){
        
        $id_customer = $customer_id;
        
        $member = Member::where('id', $id_customer)->first();
        $produk = Produk::where('sku', $sku)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }


        $id = auth()->id();
        $detail = new Cart();
        $detail->employee_id = $id;
        $detail->customer_id = $id_customer;
        $detail->product_id = $produk->id;
        
        if ($member->customer_type == 1) {
            $detail->base_price = $produk->price;
        } else if ($member->customer_type == 2) {
            $detail->base_price = $produk->offline_price;
        } else if ($member->customer_type == 3) {
            $detail->base_price = $produk->reseller_price;
        } else if ($member->customer_type == 4) {
            $detail->base_price = $produk->agen_price;
        } else {
            # code...
        }

        $detail->count = $count;
        $detail->final_price = $detail->base_price * $detail->count;
        $detail->isSpecialCase = 0;
        $detail->isSend = 0;
        $detail->flag = 0;
        $detail->save();


        return response()->json('Data berhasil ditambah', 200);
    
    }
   

    public function new()
    {
        $id_customer = session('id_customer');
        
        $carts = Cart::with('member')
                ->where('customer_id', $id_customer)
                ->where('isSend', 0)
                ->where('flag', 0)
                ->get();
        
        return datatables()
            ->of($carts)
            ->addIndexColumn()
            // ->addColumn('select_all', function ($carts) {
            //     return '
            //         <input type="checkbox" name="id[]" value="'. $carts->id .'">
            //     ';
            // })
            ->addColumn('produk', function ($carts) {
                return $carts->produk->title;
            })
            ->addColumn('jumlah', function ($carts) {
                return '<input type="number" class="form-control input-sm count" 
                data-id="'. $carts->id .'" 
                value="'. $carts->count .'">';
            })
            ->addColumn('harga', function ($carts) {
                return format_uang($carts->base_price);
            })
            ->addColumn('total', function ($carts) {
                return format_uang($carts->final_price);
            })
            ->addColumn('aksi', function ($carts) {
                if ($carts->isSpecialCase == 1) {
                    return '
                    <div class="btn-group">
                        <button onclick="deleteData(`'. route('cart.destroy', $carts->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus</button>
                    </div>
                    <label style="margin-top: 20px" class="switch">
                        <input onclick="tes('. $carts->id .', this);" id="'. $carts->id .'"  checked="true" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                    ';
                } else {
                    return '
                    <div class="btn-group">
                        <button onclick="deleteData(`'. route('cart.destroy', $carts->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus</button>
                    </div>
                    <label style="margin-top: 20px" class="switch">
                        <input onclick="tes('. $carts->id .', this);" id="'. $carts->id .'"  type="checkbox">
                        <span class="slider round"></span>
                        </label>
                    ';
                }
            })
            ->addColumn('tambahan', function ($carts) {
                return '<input type="number" class="form-control input-sm discount" data-id="'. $carts->id .'" value="'. $carts->discount .'">';
            })
            ->rawColumns(['aksi', 'select_all', 'jumlah', 'tambahan'])
            ->make(true);
    }

    public function purge_cart($id)
    {
        $cart = Cart::where('customer_id', $id)->where('isSend', 0)->where('flag', 0)->get();

        foreach ($cart as $item) {
            $produk = Produk::find($item->product_id);
            
            if ($produk->isLocal == 1) {
                $produk->stock += $item->count;
                $produk->update();
                        
                $data  = [
                    'sku' => $produk->sku,
                    'stock' => $produk->stock,
                    'status' => "local_product_purge"
                ];

                $item->delete();

            } else {
                
            $job = JobApi::where('sku', $produk->sku)->where('status' , 0)->first();
                
            if (!empty($job)) {
                $newJob = new JobApi();
                $newJob->sku = $produk->sku;
                $newJob->count = $item->count;
                $newJob->action = 'increase';
                $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                $newJob->status = 0;
                $newJob->updated_at = Carbon::now();
                $newJob->save();
    
                $produk->isSync = 0;
                $produk->stock += $item->count;
                $produk->update();
                
                $data  = [
                    'sku' => $produk->sku,
                    'stock' => $produk->stock,
                    'status' => "fail_job_purge"
                ];            

            } else {

                $produk->isSync = 0;
                $produk->update();

                try {
                    $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                        'username' => 'isitaman',
                        'password' => '1s1t4m4nJ@v1n4.',
                        'action' => 'increase',
                        'stock' => $item->count,
                        'sku' => $item->produk->sku,
                    ])['data'];
                    
                    $data  = [
                        'sku' => $response['sku'],
                        'stock' => $response['stock'],
                        'status' => 'success'
                    ];
            
                    $produk->stock = $response['stock'];
                    $produk->update();
    
                    $item->delete();
    
    
                } catch (\Throwable $th) {
                    $newJob = new JobApi();
                    $newJob->sku = $item->produk->sku;
                    $newJob->count = $item->count;
                    $newJob->action = 'increase';
                    $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                    $newJob->status = 0;
                    $newJob->updated_at = Carbon::now();
                    $newJob->save();
        
                    $produk->isSync = 0;
                    $produk->stock += $item->count;
                    $produk->update();
                    
                    $data  = [
                        'sku' => $item->produk->sku,
                        'stock' => $item->count,
                        'status' => 'fail_connect'
                    ];
    
                    $item->delete();
                }
            }           
        }
    }
    return response($data);
}

    public function purge_send($id)
    {
        $cart = Cart::where('customer_id', $id)->where('isSend', 1)->where('flag', 0)->get();

        foreach ($cart as $item) {
            $produk = Produk::find($item->product_id);
            
            if ($produk->isLocal == 1) {
                $produk->stock += $item->count;
                $produk->update();
                        
                $data  = [
                    'sku' => $produk->sku,
                    'stock' => $produk->stock,
                    'status' => "local_product_purge"
                ];

                $item->delete();

            } else {
                
            $job = JobApi::where('sku', $produk->sku)->where('status' , 0)->first();
                
            if (!empty($job)) {
                $newJob = new JobApi();
                $newJob->sku = $produk->sku;
                $newJob->count = $item->count;
                $newJob->action = 'increase';
                $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                $newJob->status = 0;
                $newJob->updated_at = Carbon::now();
                $newJob->save();
    
                $produk->isSync = 0;
                $produk->stock += $item->count;
                $produk->update();
                
                $data  = [
                    'sku' => $produk->sku,
                    'stock' => $produk->stock,
                    'status' => "fail_job_purge"
                ];            

            } else {

                $produk->isSync = 0;
                $produk->update();

                try {
                    $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                        'username' => 'isitaman',
                        'password' => '1s1t4m4nJ@v1n4.',
                        'action' => 'increase',
                        'stock' => $item->count,
                        'sku' => $item->produk->sku,
                    ])['data'];
                    
                    $data  = [
                        'sku' => $response['sku'],
                        'stock' => $response['stock'],
                        'status' => 'success'
                    ];
            
                    $produk->stock = $response['stock'];
                    $produk->update();
    
                    $item->delete();
    
    
                } catch (\Throwable $th) {
                    $newJob = new JobApi();
                    $newJob->sku = $item->produk->sku;
                    $newJob->count = $item->count;
                    $newJob->action = 'increase';
                    $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                    $newJob->status = 0;
                    $newJob->updated_at = Carbon::now();
                    $newJob->save();
        
                    $produk->isSync = 0;
                    $produk->stock += $item->count;
                    $produk->update();
                    
                    $data  = [
                        'sku' => $item->produk->sku,
                        'stock' => $item->count,
                        'status' => 'fail_connect'
                    ];
    
                    $item->delete();
                }
            }           
        }
    }
        return response($data);
    }

    public function sku($sku)
    {
    $produk = Produk::where('sku', $sku)->first();
    
    $job = JobApi::where('sku', $sku)->where('status' , 0)->first();
    
        if (!empty($job)) { 
            return response()->json('Gagal Mendapat data IsiTaman', 500);
        } else {
            
            $produk->isSync = 0;
            $produk->update();
        
            $response =  Http::get('https://pos.isitaman.com/product/getBySku?username=isitaman&password=1s1t4m4nJ@v1n4.&sku='. $sku )['data'];
            
                if (!empty($response)) {            
                        $data  = [
                            'sku' => $response['sku'],
                            'stock' => $response['stock']
                        ];
                        $produk = Produk::where('sku', $response['sku'])->first();
                        $produk->stock = $response['stock'];
                        $produk->isSync = 1;
                        $produk->update();
            
                    var_dump($data);
                    // return response($data);
                } else {
                    # code...
                }
        }
    
    }

    public function send_api($sku, $count)
    {
        $produk = Produk::where('sku', $sku)->first();
        $job = JobApi::where('sku', $sku)->where('status' , 0)->first();
        
        if (!empty($job)) {
            $produk->stock -= $count;
            $produk->update();
            $data  = [
                'status' => 'fail',
            ];
            return response($data);
        
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


