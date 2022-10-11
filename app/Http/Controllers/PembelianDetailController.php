<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Diskon;
use App\Models\JobApi;
use App\Models\ListProductTransaction;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Penjualan;
use App\Models\Produk;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;


use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $id_pembelian = session('transaction_id');
        $id_customer = session('customer_id');
        $produk = Produk::orderBy('title')->get();
        $member = Member::find(session('customer_id'));
        
        $diskon = Diskon::
        select('name', 'discount')
        ->where('status_discount', 1)
        ->get();

        $transaction = Penjualan::find(session('transaction_id'));

        if ($transaction) {
            $total=0;
            $total_item=0;
    
            $carts = Cart::where('isSend', 1)->where('flag', 0)->where('customer_id', session('customer_id'))->get();
            foreach ($carts as $cart) {
    
                $total += $cart->base_price * $cart->count;
                $total_item += $cart->count;
            }
    
            $transaction->total_price = $total;
            $transaction->update();
    
            if (! $member) {
                abort(404);
            }

        } else {
            $transaction2 = Penjualan::where('order_type', 0)->where('customer_id', $id_customer)->first();
            $total=0;
            $total_item=0;
    
            $carts = Cart::where('isSend', 1)->where('flag', 0)->where('customer_id', session('customer_id'))->get();
            foreach ($carts as $cart) {
    
                $total += $cart->base_price * $cart->count;
                $total_item += $cart->count;
            }
    
            $transaction2->total_price = $total;
            $transaction2->update();
    
            if (! $member) {
                abort(404);
            }
        }
        
        

        return view('pembelian_detail.index', compact('id_pembelian', 'id_customer', 'produk', 'member', 'carts', 'total', 'transaction', 'diskon'));
    
    }


    public function data($id)
    {
        $transaction = Penjualan::where('id', $id)->first();
        
        $detail = Cart::
        where('flag', 0)
        ->where('isSend', 1)
        ->where('customer_id', $transaction->customer_id)                        
        ->get();

        $data = array();
        $total = 0;
        $total_item = 0;
        

        foreach ($detail as $item) {
            
            $row = array();
            $row['sku'] = '<span class="label label-success">'. $item->produk['sku'] .'</span';
            $row['title'] = $item->produk['title'];
            $row['base_price']  = 'Rp. '. format_uang($item->base_price);
            $row['count']      = '<input type="number" class="form-control input-sm count" data-id="'. $item->id .'" value="'. $item->count .'">';
            $row['total_price']    = 'Rp. '. format_uang($item->base_price * $item->count);
            $row['final_price']    = 'Rp. '. format_uang($item->final_price);
            $row['diskon']      = '<input type="number" class="form-control input-sm discount" data-id="'. $item->id .'" value="'. $item->discount .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->final_price);
            
            if ($item->isSpecialCase == 1) {
                $row['aksi']        = '<div class="btn-group">
                    <button onclick="deleteData(`'. route('cart.destroy', $item->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus produk</button>
                    <label class="switch">
                    <input onclick="tes('. $item->id .', this);" id="'. $item->id .'"  checked="true" type="checkbox">
                    <span class="slider round"></span>
                    </label>
                </div>';            
            
            } else if($item->isSpecialCase == 0) {
                $row['aksi']        = '<div class="btn-group">
                <button onclick="deleteData(`'. route('cart.destroy', $item->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus produk</button>
                <label class="switch">
                <input onclick="tes('. $item->id .', this);" id="'. $item->id .'"   type="checkbox">
                <span class="slider round"></span>
                </label>
            </div>';   
            } else {
                
            }
            
            
            $data[] = $row;
            $total += $item->final_price;
            $total_item += $item->count;
        }

        $transaction->total_price = $total;
        $transaction->update();

        $data[] = [
            'sku' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'title' => '',
            'base_price'  => '',
            'count'      => '',
            'total_price'    => '',
            'final_price'    => '',
            'diskon'    => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'sku', 'count', 'diskon'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        

        return response()->json('Data berhasil disimpan', 200);
    }

    public function addProduct($sku, $count, $customer_id){
        
        $id_customer = $customer_id;
        $member = Member::where('id', $id_customer)->first();
        $produk = Produk::where('sku', $sku)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        
        if ($produk->stock < $count) {
            $data  = [
                'status' => 'fail_stok',
            ]; 
            return response($data);
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
        $detail->isSend = 1;
        $detail->flag = 0;
        $detail->save();


        return response()->json('Data berhasil ditambah', 200);
    
    }
    
    public function case($id, $case)
    {
        $cart = Cart::find($id);
        $cart->final_price = $cart->base_price * $cart->count;

        if ($case == "true") {
            
            if ( 0 < $cart->discount && $cart->discount <= 100 ) {
                $cart->discount = $cart->discount;
                $x = $cart->discount / 100 * $cart->final_price;
                $cart->final_price = $cart->final_price + $x;
            } else {
                $cart->discount = $cart->discount;
                $cart->final_price = $cart->final_price + $cart->discount;
            }
            
            $cart->isSpecialCase = 1;
            $cart->update();
            $data  = [
                'status' => "plus",
            ];
            
        } else {
            if ( 0 < $cart->discount && $cart->discount <= 100 ) {
                $cart->discount = $cart->discount;
                $x = $cart->discount / 100 * $cart->final_price;
                $cart->final_price = $cart->final_price - $x;
            } else {
                $cart->discount = $cart->discount;
                $cart->final_price = $cart->final_price - $cart->discount;
            }
            
            $cart->isSpecialCase = 0;
            $cart->update();
            $data  = [
                'status' => "minus",
            ];
        }

        return response($data);
    }

    public function counter(Request $request, $id)
    {
        $id = $request->id;
        $count = $request->jumlah; //data sekarang
        
        $detail = Cart::find($id); //cart sebelum
        
        $detail->final_price = $detail->base_price * $detail->count;
        
        $produk = Produk::find($detail->product_id);
        $stok = $produk->stock;
        $sku = $produk->sku;
        if ($count != 0) {
                
                if ($detail->count == $count) { 
                        $detail->count = $count;
                        $detail->final_price = $detail->base_price * $count;
                    } else {

                        if ($detail->count > $count) { //sebelum lebih besar dari sekarang , send
                            $x = $detail->count - $count;
                            $detail->count = $count;
                            $detail->final_price = $detail->base_price * $count;

                              //send stock
                              $job = JobApi::where('sku', $sku)->where('status' , 0)->first();
                                if (!empty($job)) {
                                    return redirect()->route('job_api.index');
                                
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
                                            'stock' => $response['stock']
                                        ];
                                
                                        $produk->stock = $response['stock'];
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
                                        $produk->update();
                                        
                                        return response()->json('Gagal Mengirim data ke IsiTaman', 500);
                                    }       
                                }  
                        } else {
                            $y = $count - $detail->count;
                            $detail->count = $count;
                            $detail->final_price = $detail->base_price * $count;
                            //remove Stok
                            $job = JobApi::where('sku', $sku)->where('status' , 0)->first();
                                    if (!empty($job)) {
                                        return redirect()->route('job_api.index');
                                    
                                    } else {
                                        try {
                                            $response = Http::put('https://pos.isitaman.com/product/updateBySku', [
                                                'username' => 'isitaman',
                                                'password' => '1s1t4m4nJ@v1n4.',
                                                'action' => 'decrease',
                                                'stock' => $y,
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
                                            $newJob->count = $y;
                                            $newJob->action = 'decrease';
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
                
                
                
            
            
        } else {
            return response()->json('Jumlah harus lebih dari 0', 400);

        }
        $detail->update();
    }
    
    public function update(Request $request, $id)
    {
        $transaction = Penjualan::find(session('transaction_id'));
        $total=0;
        $total_item=0;
        $carts = Cart::where('customer_id', session('customer_id'))->get();

        $detail = Cart::find($id);
        $diskon = $request->discount;
        $detail->final_price = $detail->base_price * $detail->count;

        if ($detail->isSpecialCase == 1) {
            if ($diskon != 0) {
                if ( 0 < $diskon && $diskon <= 100 ) {
                    $detail->discount = $diskon;
                    $x = $diskon / 100 * $detail->final_price;
                    $detail->final_price = $detail->final_price + $x;
                } else {
                    $detail->discount = $diskon;
                    $detail->final_price = $detail->final_price + $diskon;
                }
            } else {
                $detail->discount = 0;
            }
            $detail->update();        
        
        } else if ($detail->isSpecialCase == 0) {
            if ($diskon != 0) {
                if ( 0 < $diskon && $diskon <= 100 ) {
                    $detail->discount = $diskon;
                    $x = $diskon / 100 * $detail->final_price;
                    $detail->final_price = $detail->final_price - $x;
                } else {
                    $detail->discount = $diskon;
                    $detail->final_price = $detail->final_price - $diskon;
                }
            } else {
                $detail->discount = 0;
            }
            $detail->update();

        } else {
            # code...
        }
        
        foreach ($carts as $cart) {
            
            $total += $cart->final_price;
            $total_item += $cart->count;
        }
        
        if ($transaction) {
            $transaction->total_price = $total;
            $transaction->update();
        } else {
        }
        
    }

    public function destroy($id)
    {
        $detail = Pembelian::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($discount, $total)
    {
        $diskon = Diskon::where('discount', $discount)->where('status_discount' , 1)->first();
        $counter = $diskon->counter;
        $count_limit = $diskon->count_limit;
        $limit = $diskon->discount_limit;

        if ($count_limit == 0) {
            if ($diskon->discount >100) {
                $bayar = $total - $diskon->discount;
                $data  = [
                    'totalrp' => format_uang($bayar),
                    'bayar' => $bayar,
                    'bayarrp' => format_uang($bayar),
                    'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                ];
            } elseif ($diskon->discount == 0) {
                $bayar = $total;
                $data  = [
                    'totalrp' => format_uang($bayar),
                    'bayar' => $bayar,
                    'bayarrp' => format_uang($bayar),
                    'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                ];
            } elseif (0 < $diskon->discount && $diskon->discount < 100) {
                $x = $diskon->discount / 100 * $total;
                if ($x <= $limit) {
                    $bayar = $total - $x;
                    $data  = [
                        'totalrp' => format_uang($bayar),
                        'bayar' => $bayar,
                        'bayarrp' => format_uang($bayar),
                        'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                    ];            
                } else {
                    $bayar = $total - $limit;
                    $data  = [
                        'totalrp' => format_uang($bayar),
                        'bayar' => $bayar,
                        'bayarrp' => format_uang($bayar),
                        'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                    ];            
                }
            } else {
                $bayar = $total;
                $data  = [
                    'totalrp' => format_uang($bayar),
                    'bayar' => $bayar,
                    'bayarrp' => format_uang($bayar),
                    'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                ];        
            }        
        } else {
            if ($counter == $count_limit) {
                $data  = [
                    'status' => "limit",
                    'totalrp' => format_uang($total),
                    'bayar' => $total,
                    'bayarrp' => format_uang($total),
                    'terbilang' => ucwords(terbilang($total). ' Rupiah')
                ];            
            } elseif ($counter < $count_limit) {
                if ($diskon->discount >100) {
                    $bayar = $total - $diskon->discount;
                    $data  = [
                        'totalrp' => format_uang($bayar),
                        'bayar' => $bayar,
                        'bayarrp' => format_uang($bayar),
                        'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                    ];
                } elseif ($diskon->discount == 0) {
                    $bayar = $total;
                    $data  = [
                        'totalrp' => format_uang($bayar),
                        'bayar' => $bayar,
                        'bayarrp' => format_uang($bayar),
                        'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                    ];
                } elseif (0 < $diskon->discount && $diskon->discount < 100) {
                    $x = $diskon->discount / 100 * $total;
                    if ($x <= $limit) {
                        $bayar = $total - $x;
                        $data  = [
                            'totalrp' => format_uang($bayar),
                            'bayar' => $bayar,
                            'bayarrp' => format_uang($bayar),
                            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                        ];            
                    } else {
                        $bayar = $total - $limit;
                        $data  = [
                            'totalrp' => format_uang($bayar),
                            'bayar' => $bayar,
                            'bayarrp' => format_uang($bayar),
                            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                        ];            
                    }
                } else {
                    $bayar = $total;
                    $data  = [
                        'totalrp' => format_uang($bayar),
                        'bayar' => $bayar,
                        'bayarrp' => format_uang($bayar),
                        'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
                    ];        
                }
            }             
            else {
                $data  = [
                    'status' => "limit",
                    'totalrp' => format_uang($total),
                    'bayar' => $total,
                    'bayarrp' => format_uang($total),
                    'terbilang' => ucwords(terbilang($total). ' Rupiah')
                ];  
            }
        }
        return response()->json($data);
    }

    public function sku($sku)
    {
        // $client = new Client(['base_uri' => 'http://pos.isitaman.com/product/']);
        // $x = '';
        // $response = $client->request('GET', '/getBySku' , ['form_params' => [
        //     'username' => 'isitaman',
        //     'password' => '1s1t4m4nJ@v1n4.',
        //     'sku' => $sku
        // ]]);

        // echo $response->getBody();

        // $data  = [
        //     'sku' => $sku,
        // ];

        // return response()->$response->getBody();
        return Http::get('http://pos.isitaman.com/product/getBySku?username=isitaman&password=1s1t4m4nJ@v1n4.&sku='. $sku )['data'];
    }

    public function kembalian($kembalian, $total)
    {
        if ($kembalian == 0) {
            $data = [
                'sisa' => 0,
                'terbilang' => '0 Rupiah'
            ];
        } else {
            $sisa = $kembalian - $total;
            $data = [
                'sisa' => format_uang($sisa),
                'terbilang' => ucwords(terbilang($sisa). ' Rupiah')
            ];        
        }
        
        return response()->json($data);
    }
}
