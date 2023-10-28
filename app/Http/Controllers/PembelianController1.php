<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Diskon;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\ListProductTransaction;
use Illuminate\Support\Facades\Http;
use App\Models\JobApi;
use Carbon\Carbon;




use GrahamCampbell\ResultType\Success;

class PembelianController extends Controller
{
    public function index()
    {
        $member = Member::distinct('customer_type')->get();
        $cart = Cart::with('member')
        ->select('customer_id', 'employee_id')
        ->where('isSend', 1)
        ->where('flag', 0)
        ->distinct()
        ->get();

        return view('pembelian.index', compact('member','cart'));
    }

    public function order_online()
    {
        $member = Member::distinct('customer_type')->get();
        $cart = Cart::with('member')
        ->select('customer_id', 'employee_id')
        ->where('isSend', 1)
        ->where('flag', 0)
        ->distinct()
        ->get();

        return view('pembelian.order_online', compact('member','cart'));
    }

    public function only_transaksi()
    {
        $member = Member::distinct('customer_type')->get();
        $cart = Cart::with('member')
        ->select('customer_id', 'employee_id')
        ->where('isSend', 1)
        ->where('flag', 0)
        ->distinct()
        ->get();

        return view('pembelian.index_view', compact('member','cart'));
    }
    
    public function data()
    {
        $penjualan = Pembelian::orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($penjualan) {
                return $penjualan->created_at;
            })
            ->addColumn('customer', function ($penjualan) {
                return $penjualan->member->name;
            })
            ->addColumn('number_ref', function ($penjualan) {
                return '<span class="label label-success">'. $penjualan->number_ref .'</span>';
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->total_payment);
            })
            ->editColumn('payment_method', function ($penjualan) {
                return $penjualan->payment_method;
            })
            ->addColumn('employee_id', function ($penjualan) {
                return $penjualan->user->name;
            })
            ->addColumn('transaction_status', function ($penjualan) {
                if ($penjualan->transaction_status == 1) {
                    return '<span class="label label-success">sukses</span>';
                } elseif ($penjualan->transaction_status == 2) {
                    return '<span class="label label-danger">refund</span>';
                } else {
                    return '<span class="label label-warning">Delay</span>';
                }                
            })
            ->addColumn('aksi', function ($penjualan) {
                if ($penjualan->transaction_status == 1) {
                    return '
                    <button style="margin-top: 5px" onclick="cetakNota(`'. route('pembelian.each_nota', $penjualan->id) .'`)" class="btn btn-xs btn-warning "><i class="fa fa-save"> Cetak Nota</i></button>
                    <br>
                    <button style="margin-top: 5px" onclick="showDetail(`'. route('pembelian.show', $penjualan->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-eye"> Detail</i></button>
                    <br>
                    <button style="margin-top: 5px" onclick="refund(`'. route('pembelian.refund', $penjualan->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"> Refund</i></button>
                    ';
                } else {
                    return '
                    <button onclick="showDetail(`'. route('pembelian.show', $penjualan->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-eye"> Detail</i></button>
                    ';   
                }
            })
            ->rawColumns(['transaction_status', 'number_ref' , 'aksi'])
            ->make(true);
    }

    public function data_online()
    {
        $penjualan = Pembelian::orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($penjualan) {
                return $penjualan->created_at;
            })
            ->addColumn('customer', function ($penjualan) {
                return $penjualan->member->name;
            })
            ->addColumn('number_ref', function ($penjualan) {
                return '<span class="label label-success">'. $penjualan->number_ref .'</span>';
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->total_payment);
            })
            ->editColumn('payment_method', function ($penjualan) {
                return $penjualan->payment_method;
            })
            ->addColumn('employee_id', function ($penjualan) {
                return $penjualan->user->name;
            })
            ->addColumn('transaction_status', function ($penjualan) {
                if ($penjualan->transaction_status == 1) {
                    return '<span class="label label-success">sukses</span>';
                } elseif ($penjualan->transaction_status == 2) {
                    return '<span class="label label-danger">refund</span>';
                } else {
                    return '<span class="label label-warning">Delay</span>';
                }                
            })
            ->addColumn('aksi', function ($penjualan) {
                if ($penjualan->transaction_status == 1) {
                    return '
                    <button style="margin-top: 5px" onclick="cetakNota(`'. route('pembelian.each_nota', $penjualan->id) .'`)" class="btn btn-xs btn-warning "><i class="fa fa-save"> Cetak Nota</i></button>
                    <br>
                    <button style="margin-top: 5px" onclick="showDetail(`'. route('pembelian.show', $penjualan->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-eye"> Detail</i></button>
                    <br>
                    <button style="margin-top: 5px" onclick="refund(`'. route('pembelian.refund', $penjualan->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"> Refund</i></button>
                    ';
                } else {
                    return '
                    <button onclick="showDetail(`'. route('pembelian.show', $penjualan->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-eye"> Detail</i></button>
                    ';   
                }
            })
            ->rawColumns(['transaction_status', 'number_ref' , 'aksi'])
            ->make(true);
    }

    public function create($id)
    {
        $cart    = Cart::select('id', 'employee_id')->where('customer_id', $id)->get();
        
        
        // $bug = Pembelian::where('transaction_status', 0)->get();
        // foreach ($bug as $item) {
        //     $item->delete();
        // }

        $x = auth()->id();
        $pembelian = new Penjualan();
        $pembelian->order_type          = 0;
        $pembelian->number_ref          = 0;
        $pembelian->customer_id         = $id;
        $pembelian->total_price         = 0;
        $pembelian->total_payment       = 0;
        $pembelian->order_price         = 0;
        $pembelian->payment_method      = '';
        $pembelian->employee_id         = $x;
        $pembelian->transaction_status  = 0;
        $pembelian->invoice_status      = '';

        $pembelian->save();

        session(['transaction_id' => $pembelian->id]);
        session(['customer_id' => $pembelian->customer_id]);

        return redirect()->route('pembelian_detail.index');

    }

    public function create_online($id)
    {
        $cart    = Cart::select('id', 'employee_id')->where('customer_id', $id)->get();
        
        // $bug = Pembelian::where('transaction_status', 0)->get();
        // foreach ($bug as $item) {
        //     $item->delete();
        // }

        $x = auth()->id();
        $pembelian = new Penjualan();
        $pembelian->order_type          = 0;
        $pembelian->number_ref          = 0;
        $pembelian->customer_id         = $id;
        $pembelian->total_price         = 0;
        $pembelian->total_payment       = 0;
        $pembelian->order_price         = 0;
        $pembelian->payment_method      = '';
        $pembelian->employee_id         = $x;
        $pembelian->transaction_status  = 0;
        $pembelian->invoice_status      = '';

        $pembelian->save();

        session(['transaction_id_ON' => $pembelian->id]);
        session(['customer_id_ON' => $pembelian->customer_id]);

        return redirect()->route('pembelian_detail.index_online');

    }

    public function store(Request $request)
    {
                
        $pembelian = Pembelian::findOrFail($request->id_pembelian);
        $member = Member::where('id', $pembelian->customer_id)->first();
        $x = $member->customer_type;

        $today = date("dmY");                           // 20010310
        $id = $pembelian->id;
        
        if ($request->order_type == 1) {
            if ($x == 1) {
                $pembelian->number_ref = 'OF01-' . $today . '-'. $id;
            } elseif ($x == 2) {
                $pembelian->number_ref = 'OF02-' . $today . '-'. $id;
            } elseif ($x == 3) {
                $pembelian->number_ref = 'OF03-' . $today . '-'. $id;
            } else {
                $pembelian->number_ref = 'OF04-' . $today . '-'. $id;
            }
        } else {
            if ($x == 1) {
                $pembelian->number_ref = 'ON01-' . $today . '-'. $id;
            } elseif ($x == 2) {
                $pembelian->number_ref = 'ON02-' . $today . '-'. $id;
            } elseif ($x == 3) {
                $pembelian->number_ref = 'ON03-' . $today . '-'. $id;
            } else {
                $pembelian->number_ref = 'ON04-' . $today . '-'. $id;
            }
        }

        
        
        $pembelian->total_price = $request->total_price;
        
        
        $pembelian->created_at = $request->created_at;
        $pembelian->pay = $request->no;
        $pembelian->transaction_status = 1;
        $pembelian->discount = $request->diskon;
        $pembelian->order_type = $request->order_type;
        $pembelian->order_price = $request->order_price; //
        $pembelian->transfer_date = $request->transfer_date;
        $pembelian->name_sender = $request->name_sender;
        $pembelian->catatan = $request->catatan; //


        $pembelian->total_price = $request->total_price;
        $pembelian->total_payment = (int)$request->total_price + (int)$request->order_price;

        // if ($pembelian->pay = $pembelian->total_price) {
        //     $x = $pembelian->discount;
        //     if (0 < $x && $x <= 100) {
        //         $pembelian->pay = $pembelian->pay - ($x / 100 * $pembelian->total_price);
        //     } else {
        //         $pembelian->pay = $pembelian->pay;
        //     }
        // } else {
        //     # code...
        // }
        $pembelian->payment_method = $request->payment_method;
        
        $x = $pembelian->invoice_status;
        
        if ($x == 1) {
            $pembelian->invoice_status = 1;

            $invoice = Invoice::where('transaction_id', $request->id_pembelian)->where('status', 0)->first();
            $invoice->number_invoice = 'INV-' . $invoice->id . '-'. $pembelian->id;
            $invoice->number_ref = $pembelian->number_ref;
            $invoice->invoice_amount = $pembelian->total_payment;
            $invoice->invoice_debt = $pembelian->total_payment;
            $invoice->update();

        } else {
            $pembelian->invoice_status = 0;
        }
        
        $pembelian->update();
        
        $bayar = $request->bayar;
        $total=$request->total;
        $diskon=$request->diskon;
        
        $total_price = 0;
        
        $data_discount = Diskon::where('status_discount', 1)->where('discount', $diskon)->first();
        if ($data_discount->count_limit > 0) {
            $data_discount->counter += 1;
            $data_discount->update();

            $limit = $data_discount->count_limit;
            $counter = $data_discount->counter;
            
            if ($limit == $counter) {
                $data_discount->status_discount = 0;
                $data_discount->update();
            } else {
            }
            
        } else {

        }
        
        
        $carts = Cart::where('isSend', 1)
        ->where('flag', 0)
        ->where('customer_id', $pembelian->customer_id)
        ->get();

        foreach ($carts as $cart) {

            $product = Produk::find($cart->product_id);
            
            $list_product_transaction = new ListProductTransaction();
            $list_product_transaction->transaction_id = $pembelian->id;
            $list_product_transaction->product_id = $cart->product_id;
            $list_product_transaction->base_price = $cart->base_price;
            $list_product_transaction->final_price = $cart->final_price;
            $list_product_transaction->count = $cart->count;
            $list_product_transaction->isSpecialCase = $cart->isSpecialCase;
            $list_product_transaction->discount = $cart->discount;
            $list_product_transaction->volmetric = $product->volmetric;
            $list_product_transaction->handling_fee = $product->handling_fee;

            $list_product_transaction->save();

            $total_price += $cart->final_price;

        }

        $pembelian->total_price = $total_price;
        $pembelian->update();
        
        $cart = Cart::where('customer_id', $pembelian->customer_id)->get();
        foreach ($cart as $item) {
            $item->flag = 1;
            $item->update();
        }
        
        session(['total' => $total]);
        session(['diskon' => $diskon]);
        session(['bayar' => $bayar]);

        return redirect()->route('pembelian.selesai');
    }

    public function show($id)
    {
        $detail = ListProductTransaction::where('transaction_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->sku .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->title;
            })
            ->addColumn('base_price', function ($detail) {
                return 'Rp. '. format_uang($detail->base_price);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->count);
            })
            ->addColumn('total_price', function ($detail) {
                return 'Rp. '. format_uang($detail->base_price * $detail->count);
            })
            ->addColumn('diskon', function ($detail) {
                if ( 0 < $detail->discount && $detail->discount <= 100 ) {
                    return $detail->discount . ' %';
                } else {
                    return 'Rp. '. format_uang($detail->discount);
                }
            })
            ->addColumn('final_price', function ($detail) {
                return 'Rp. '. format_uang($detail->final_price);
            })
            ->addColumn('special_case', function ($detail) {
                if ($detail->isSpecialCase > 0 ) {
                    return 'Special Case';
                } else {
                    return 'Normal';
                }
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);
        $detail    = ListProductTransaction::where('transaction_id', $pembelian->id)->get();
        
        foreach ($detail as $item) {
            $produk = Produk::find($item->id);
            if ($produk) {
                $produk->stock += $item->count;
                $produk->update();
            }
            $item->delete();
        }

        $pembelian->delete();

        return response(null, 204);
    }

    public function bug()
    {
        $pembelian = Pembelian::where('order_type', 0)->get();
        $invoice = Invoice::where('number_ref', 0)->get();

        foreach ($pembelian as $item) {

            $item->delete();
        }

        // foreach ($invoice as $item) {

        //     $item->delete();
        // }

        return response('sukses');
    }

    public function refund($id)
    {
        $pembelian = Pembelian::find($id);
        $detail    = ListProductTransaction::where('transaction_id', $pembelian->id)->get();
        
        foreach ($detail as $item) {
            $produk = Produk::find($item->product_id);
            
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
                    $produk->stock_isitaman = $response['stock'];
                    $produk->isSync = 1;
                    $produk->update();
    
                    $item->status = 1;
                    $item->update();    
    
                } catch (\Throwable $th) {
                    $newJob = new JobApi();
                    $newJob->sku = $item->produk->sku;
                    $newJob->count = $item->count;
                    $newJob->action = 'increase';
                    $newJob->endpoint = 'https://pos.isitaman.com/product/updateBySku';
                    $newJob->status = 0;
                    $newJob->updated_at = Carbon::now();
                    $newJob->save();
        
                    $produk->stock += $item->count;
                    $produk->isSync = 0;
                    $produk->update();
                    
                    $data  = [
                        'sku' => $item->produk->sku,
                        'stock' => $item->count,
                        'status' => 'fail'
                    ];
    
                    $item->status = 2;
                    $item->update();
                }
                        
        }

        $pembelian->transaction_status = 2;
        $pembelian->update();

        return response($data);
    }

    public function selesai()
    {
        return view('pembelian.selesai');
    }


    public function nota(){
        $id_penjualan = session('transaction_id');
        $id_customer = session('customer_id');
        $total = session('total');
        $diskon = session('diskon');
        $bayar = session('bayar');
       
        $transaksi = Pembelian::find(session('transaction_id'));
        $customer = Member::find(session('customer_id'));
        $detail = ListProductTransaction::where('transaction_id', $id_penjualan)->get();
        $tgl = $transaksi->created_at;
        // $detail = Cart::
        // where('flag', 1)
        // ->where('isSend', 1)
        // ->where('customer_id', $customer->id)                        
        // ->get();

    
       
        return view('pembelian.nota', compact('detail', 'transaksi', 'customer','total','diskon', 'bayar', 'tgl'));

    }

    public function each_nota($id){
        $transaksi = Pembelian::find($id);
        $x = $transaksi->$id;

        $detail = ListProductTransaction::where('transaction_id', $id)->get();

        $lpt = Cart::
        where('flag', 1)
        ->where('isSend', 1)
        ->where('customer_id', $transaksi->customer_id)                        
        ->get();
        //customer
        //lpt
        $total = $transaksi->total_price;

       
        return view('pembelian.nota', compact('transaksi', 'detail', 'lpt', 'total'));

    }
    function list_cart (){
        
        $member = Member::distinct('customer_type')->get();

        return view('pembelian.list_cart', compact('member'));

        // return redirect()->route('cart.index');
    
    }

}
