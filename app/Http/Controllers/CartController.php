<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Member;
use App\Models\Cart;
use App\Models\CartTransaction;
use App\Models\CustomerType;
use App\Models\Diskon;
use App\Models\Invoice;
use App\Models\JobApi;
use App\Models\ListProductTransaction;
use App\Models\NomorNota;
use App\Models\Pembelian;
use App\Models\Price;
use App\Models\PriceRules;
use App\Models\Produk;
use App\Models\ProdukNew;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
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
        $customer = Member::orderBy('id')->get();
        $customer_type = CustomerType::orderBy('id')->get();

        return view('cart.index', compact('member', 'customer', 'customer_type'));
    }
    public function index_kasir()
    {
        $member = Member::distinct('customer_type')->get();
        $customer = Member::orderBy('id')->get();
        $customer_type = CustomerType::orderBy('id')->get();

        return view('cart.index_kasir', compact('member', 'customer', 'customer_type'));
    }
    public function index_staff()
    {
        $member = Member::distinct('customer_type')->get();
        $customer = Member::orderBy('id')->get();
        $customer_type = CustomerType::orderBy('id')->get();

        return view('cart.index_staff', compact('member', 'customer', 'customer_type'));
    }

    public function data()
    {
        $carts = Cart::
                select('customer_id', 'employee_id', 'isSend','nomor_nota')
                ->where('flag', 0)
                ->distinct('nomor_nota')
                // ->groupBy('nomor_nota')
                ->get();
        // $x = auth()->id();
        // $employee = User::where('id', $x)->first();
        // $level = $employee->level;

        // <input class="largerCheckbox" type="checkbox" name="id[]" value="'. $carts->customer_id .'&'. $carts->employee_id.'">

        
        return datatables()
            ->of($carts)
            ->addIndexColumn()
            ->addColumn('select_all', function ($carts) {
                return '
                    <input class="largerCheckbox" type="checkbox" name="id[]" value="'. $carts->nomor_nota.'">
                ';
            })
            ->addColumn('nomor_nota', function ($carts) {
                return $carts->nomor_nota;
            })
            ->addColumn('customer', function ($carts) {
                return $carts->member->name;
            })
            ->addColumn('employee', function ($carts) {
                return $carts->user->name;
            })
            // ->addColumn('isSend', function ($carts) {
            //     if ($carts->isSend == 1) {
            //         return "Terkirim ke Kasir";
            //     } else {
            //         return "Delay";
            //     }
            // })
            //                        <button onclick="deleteData(`'. route('cart.purge_send', $carts->customer_id) .'`)" class="btn btn-sm btn-danger btn-block"><i class="fa fa-trash"> Hapus</i></button>

            ->addColumn('aksi', function ($carts) {

                if ($carts->user->level == 3) {
                    # code...
                } else {
                    # code...
                }
                
                if ($carts->isSend == 1) {
                    return '
                    <div class="btn-group">
                        <button onclick="showDetail(`'. route('cart.show_send', $carts->customer_id) .'`)" class="btn btn-sm btn-info btn-block"><i class="fa fa-eye"> Detail</i></button>    
                    </div>
                    ';                
                } else {
                    return '
                    <div class="btn-group">
                        <a href="cart/'. ($carts->nomor_nota) .'/edit_cart" class="btn btn-sm btn-warning btn-block"><i class="fa fa-plus-square"> Edit</i></a>    
                        <button onclick="showDetail(`'. route('cart.show_cart', $carts->nomor_nota) .'`)" class="btn btn-sm btn-info btn-block"><i class="fa fa-eye"> Detail</i></button>    
                        <button onclick="deleteData(`'. route('cart.purge_cart', $carts->nomor_nota) .'`)" class="btn btn-sm btn-danger btn-block"><i class="fa fa-trash"> Hapus</i></button>
                    </div>
                    ';
                }                
            })
            ->rawColumns(['aksi', 'select_all'])
            ->make(true);
    }

    public function data_staff()
    {
        $carts = Cart::
                select('customer_id', 'employee_id', 'isSend','nomor_nota')
                ->where('flag', 0)
                ->distinct('nomor_nota')
                // ->groupBy('nomor_nota')
                ->get();
        // $x = auth()->id();
        // $employee = User::where('id', $x)->first();
        // $level = $employee->level;

        // <input class="largerCheckbox" type="checkbox" name="id[]" value="'. $carts->customer_id .'&'. $carts->employee_id.'">

        
        return datatables()
            ->of($carts)
            ->addIndexColumn()
            ->addColumn('select_all', function ($carts) {
                return '
                    <input class="largerCheckbox" type="checkbox" name="id[]" value="'. $carts->nomor_nota.'">
                ';
            })
            ->addColumn('nomor_nota', function ($carts) {
                return $carts->nomor_nota;
            })
            ->addColumn('customer', function ($carts) {
                return $carts->member->name;
            })
            ->addColumn('employee', function ($carts) {
                return $carts->user->name;
            })
            // ->addColumn('isSend', function ($carts) {
            //     if ($carts->isSend == 1) {
            //         return "Terkirim ke Kasir";
            //     } else {
            //         return "Delay";
            //     }
            // })
            ->addColumn('aksi', function ($carts) {
                
                if ($carts->isSend == 1) {
                    return '
                    <div class="btn-group">
                        <button onclick="showDetail(`'. route('cart.show_send', $carts->customer_id) .'`)" class="btn btn-sm btn-info btn-block"><i class="fa fa-eye"> Detail</i></button>    
                    </div>
                    ';                
                } else {
                    return '
                    <div class="btn-group">
                        <a href="'. ($carts->nomor_nota) .'/edit_cart" class="btn btn-sm btn-warning btn-block"><i class="fa fa-plus-square"> Edit</i></a>    
                        <button onclick="showDetail(`'. route('cart.show_cart', $carts->nomor_nota) .'`)" class="btn btn-sm btn-info btn-block"><i class="fa fa-eye"> Detail</i></button>    
                        <button onclick="deleteData(`'. route('cart.purge_cart', $carts->nomor_nota) .'`)" class="btn btn-sm btn-danger btn-block"><i class="fa fa-trash"> Hapus</i></button>
                    </div>
                    ';
                }                
            })
            ->rawColumns(['aksi', 'select_all'])
            ->make(true);
    }

    public function data_kasir()
    {
        $carts = Cart::
                select('customer_id', 'employee_id', 'isSend','nomor_nota')
                ->where('flag', 0)
                ->distinct('nomor_nota')
                // ->groupBy('nomor_nota')
                ->get();
        // $x = auth()->id();
        // $employee = User::where('id', $x)->first();
        // $level = $employee->level;

        // <input class="largerCheckbox" type="checkbox" name="id[]" value="'. $carts->customer_id .'&'. $carts->employee_id.'">

        
        return datatables()
            ->of($carts)
            ->addIndexColumn()
            ->addColumn('select_all', function ($carts) {
                return '
                    <input class="largerCheckbox" type="checkbox" name="id[]" value="'. $carts->nomor_nota.'">
                ';
            })
            ->addColumn('nomor_nota', function ($carts) {
                return $carts->nomor_nota;
            })
            ->addColumn('customer', function ($carts) {
                return $carts->member->name;
            })
            ->addColumn('employee', function ($carts) {
                return $carts->user->name;
            })
            // ->addColumn('isSend', function ($carts) {
            //     if ($carts->isSend == 1) {
            //         return "Terkirim ke Kasir";
            //     } else {
            //         return "Delay";
            //     }
            // })
            ->addColumn('aksi', function ($carts) {
                
                if ($carts->isSend == 1) {
                    return '
                    <div class="btn-group">
                        <button onclick="showDetail(`'. route('cart.show_send', $carts->customer_id) .'`)" class="btn btn-sm btn-info btn-block"><i class="fa fa-eye"> Detail</i></button>    
                    </div>
                    ';                
                } else {
                    return '
                    <div class="btn-group">
                        <button onclick="showDetail(`'. route('cart.show_cart', $carts->nomor_nota) .'`)" class="btn btn-sm btn-warning btn-block"><i class="fa fa-eye"> Detail</i></button>    
                    </div>
                    ';
                }                
            })
            ->rawColumns(['aksi', 'select_all'])
            ->make(true);
    }


    /**                    

     * <button onclick="sendCart(`'. ($carts->customer_id) .'`)" class="btn btn-sm btn-success btn-block"><i class="fa fa-shopping-basket"> Kasir</i></button>    
                 

     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create($id){
        
        
        if ($id == 0) {
            $id_customer = session('id_customer');
            
            // $produk = ProdukMaster::get();
            $produk = ProdukNew::where('enable', 1)->orderBy('nama_produk', 'asc')->get();
            $customer = Member::where('id', $id_customer) ->first();
            $member = Member::all();            
            
            
        } else {
            
            $id_customer = $id;
            // $produk = ProdukMaster::get();
            $produk = ProdukNew::where('enable', 1)->orderBy('nama_produk', 'asc')->get();
            $customer = Member::where('id', $id) ->first();
            $member = Member::orderBy('name')->get();
            session(['id_customer' => $id]);
        }
        // $cart = Cart::whereDate('created_at', Carbon::today())->distinct('nomor_nota')->orderBy('id', 'desc')->first();
        
        $x = auth()->id();
        $today = date("dmY");                           // 20010310
        $y="";
        $nomor_nota="";

        $cek_nota = NomorNota::
        whereDate('created_at', Carbon::today())
        ->where('user_id', $x)
        ->orderBy('id', 'asc')
        ->where('status', 0)
        ->first();

        if ($cek_nota) {
            # code...
            // return $cek_nota;
            $nomor_nota = $cek_nota->nomor_nota;
            session(['nomor_nota' => $nomor_nota]);
            // $tes = session('nomor_nota');
            // return $tes;
        } else {
            # code...
            $NoNota = NomorNota::whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->first();
            // return $NoNota;

            if ($NoNota) {
                $last = $NoNota->nomor_nota;
                # code...
                $var = preg_split("#-#", $last); 
                $date = $var[0];
                $urutan = $var[1];
                // $nomor_nota=null;
                
                $y = (int) $urutan;
                $y++;
                $nomor_nota = $today . '-'. $y;

                // return $nomor_nota;

                $new = new NomorNota();
                $new->user_id = $x;
                $new->nomor_nota = $nomor_nota;
                $new->status = 0;
                $new->save();

                session(['nomor_nota' => $nomor_nota]);
                // $tes = session('nomor_nota');
                // return $tes;

            } else {
                # code...
                $nomor_nota = $today . '-1';

                $new = new NomorNota();
                $new->user_id = $x;
                $new->nomor_nota = $nomor_nota;
                $new->status = 0;
                $new->save();
    
                session(['nomor_nota' => $nomor_nota]);

                // $tes = session('nomor_nota');
                // return $tes;
            }
            // return $cek_nota;
        }
        
        return view('cart.create', compact('customer', 'produk', 'id_customer', 'member', 'nomor_nota'));
        // return redirect()->route('cart.create',compact('customer', 'produk', 'id_customer', 'member', 'nomor_nota'),  ['id' => $id])->withInput();

        // $id_customer = $id; 

        // cek nomor nota di tabel by id, if ada maka ambil, jika tidak maka buat baru dengan status 0, DONE
        // jika sudah dalam halaman cart, if addproduk status jadi 1, if batal status tetap 0 dan kembali, nomor nota bisa dipakai lagi
        // if sudah buat kemudian hapus satu cart maka cek , if dalam data cart tidak ada yg sesuai dengan nomor nota, maka status kembali ke 0
        // jika ada maka status tetap jadi 1 (terpakai)

        // dalam halaman list cart pindah acuan menggunakan nomor nota
        // begitu pula dengan show detail dan delete cart

        // $produk = Produk::get();
        // $customer = Member::where('id', $id) ->first();
        // session(['id_customer' => $id]);
        
        // return view('cart.create', compact('customer', 'produk', 'id_customer'));
    }

    public function create_staff($id){
        
        
        if ($id == 0) {
            $id_customer = session('id_customer');
            
            // $produk = ProdukMaster::get();
            $produk = ProdukNew::where('enable', 1)->orderBy('nama_produk', 'asc')->get();
            $customer = Member::where('id', $id_customer) ->first();
            $member = Member::all();            
            
            
        } else {
            
            $id_customer = $id;
            // $produk = ProdukMaster::get();
            $produk = ProdukNew::where('enable', 1)->orderBy('nama_produk', 'asc')->get();
            $customer = Member::where('id', $id) ->first();
            $member = Member::orderBy('name')->get();
            session(['id_customer' => $id]);
        }
        // $cart = Cart::whereDate('created_at', Carbon::today())->distinct('nomor_nota')->orderBy('id', 'desc')->first();
        
        $x = auth()->id();
        $today = date("dmY");                           // 20010310
        $y="";
        $nomor_nota="";

        $cek_nota = NomorNota::
        whereDate('created_at', Carbon::today())
        ->where('user_id', $x)
        ->orderBy('id', 'asc')
        ->where('status', 0)
        ->first();

        if ($cek_nota) {
            # code...
            // return $cek_nota;
            $nomor_nota = $cek_nota->nomor_nota;
            session(['nomor_nota' => $nomor_nota]);
            // $tes = session('nomor_nota');
            // return $tes;
        } else {
            # code...
            $NoNota = NomorNota::whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->first();
            // return $NoNota;

            if ($NoNota) {
                $last = $NoNota->nomor_nota;
                # code...
                $var = preg_split("#-#", $last); 
                $date = $var[0];
                $urutan = $var[1];
                // $nomor_nota=null;
                
                $y = (int) $urutan;
                $y++;
                $nomor_nota = $today . '-'. $y;

                // return $nomor_nota;

                $new = new NomorNota();
                $new->user_id = $x;
                $new->nomor_nota = $nomor_nota;
                $new->status = 0;
                $new->save();

                session(['nomor_nota' => $nomor_nota]);
                // $tes = session('nomor_nota');
                // return $tes;

            } else {
                # code...
                $nomor_nota = $today . '-1';

                $new = new NomorNota();
                $new->user_id = $x;
                $new->nomor_nota = $nomor_nota;
                $new->status = 0;
                $new->save();
    
                session(['nomor_nota' => $nomor_nota]);

                // $tes = session('nomor_nota');
                // return $tes;
            }
            // return $cek_nota;
        }
        
        return view('cart.create_staff', compact('customer', 'produk', 'id_customer', 'member', 'nomor_nota'));
        // return redirect()->route('cart.create',compact('customer', 'produk', 'id_customer', 'member', 'nomor_nota'),  ['id' => $id])->withInput();

        // $id_customer = $id; 

        // cek nomor nota di tabel by id, if ada maka ambil, jika tidak maka buat baru dengan status 0, DONE
        // jika sudah dalam halaman cart, if addproduk status jadi 1, if batal status tetap 0 dan kembali, nomor nota bisa dipakai lagi
        // if sudah buat kemudian hapus satu cart maka cek , if dalam data cart tidak ada yg sesuai dengan nomor nota, maka status kembali ke 0
        // jika ada maka status tetap jadi 1 (terpakai)

        // dalam halaman list cart pindah acuan menggunakan nomor nota
        // begitu pula dengan show detail dan delete cart

        // $produk = Produk::get();
        // $customer = Member::where('id', $id) ->first();
        // session(['id_customer' => $id]);
        
        // return view('cart.create', compact('customer', 'produk', 'id_customer'));
    }

    public function edit_cart($nomor_nota){
      
        $nomornota = $nomor_nota;
        $x = auth()->id();
        // $id_customer = session('id_customer');
        $cart = Cart::
        select('customer_id', 'employee_id', 'isSend','nomor_nota')
        ->where('nomor_nota', $nomor_nota)
        ->where('flag', 0)
        ->distinct('nomor_nota')
        ->get();
        // return $cart;
        foreach ($cart as $key) {
            # code...
            $customer= Member::where('id', $key->customer_id)->first();
            // return $customer;
            break;
        }
        // var_dump($cart);
        $produk = ProdukNew::where('enable', 1)->orderBy('nama_produk', 'asc')->get();
        $member = Member::all();

        return view('cart.edit', compact('customer','produk', 'member', 'nomornota'));
    }

    public function edit_data($nomor_nota)
    {
        $carts = cart::where('nomor_nota', $nomor_nota)->get();
        
        // $detail = Cart::
        // where('flag', 0)
        // ->where('isSend', 1)
        // ->where('customer_id', $transaction->customer_id)                        
        // ->get();
        return datatables()
            ->of($carts)
            ->addIndexColumn()
            // ->addColumn('select_all', function ($carts) {
            //     return '
            //         <input type="checkbox" name="id[]" value="'. $carts->id .'">
            //     ';
            // })
            ->addColumn('sku', function ($carts) {
                return $carts->produk->sku;
            })
            ->addColumn('produk', function ($carts) {
                return $carts->produk->nama_produk;
            })
            ->addColumn('jumlah', function ($carts) {
                return '<input type="number" class="form-control input-sm count" 
                data-id="'. $carts->id .'" 
                value="'. $carts->count .'">';
            })
            ->addColumn('harga', function ($carts) {
                return '<input type="number" class="form-control input-sm price" 
                data-id="'. $carts->id .'" 
                value="'. $carts->base_price .'">';
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
                    <label class="switch">
                        <input onclick="tes('. $carts->id .', this);" id="'. $carts->id .'"  checked="true" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                    ';
                } else {
                    return '
                    <div class="btn-group">
                        <button onclick="deleteData(`'. route('cart.destroy', $carts->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus</button>
                    </div>
                    ';
                }
            })
            ->addColumn('tambahan', function ($carts) {
                return '<input type="number" class="form-control input-sm discount" data-id="'. $carts->id .'" value="'. $carts->discount .'">';
            })
            ->rawColumns(['aksi', 'select_all', 'jumlah', 'tambahan', 'harga'])
            ->make(true);
        // $data = array();
        // $total = 0;
        // $total_item = 0;
        

        // foreach ($cart as $item) {
            
        //     $row = array();
        //     $row['sku'] = '<span class="label label-success">'. $item->produk['sku'] .'</span';
        //     $row['title'] = $item->produk['title'];
        //     $row['base_price']  = 'Rp. '. format_uang($item->base_price);
        //     $row['count']      = '<input type="number" class="form-control input-sm count" data-id="'. $item->id .'" value="'. $item->count .'">';
        //     $row['total_price']    = 'Rp. '. format_uang($item->base_price * $item->count);
        //     $row['final_price']    = 'Rp. '. format_uang($item->final_price);
        //     $row['diskon']      = '<input type="number" class="form-control input-sm discount" data-id="'. $item->id .'" value="'. $item->discount .'">';
        //     $row['subtotal']    = 'Rp. '. format_uang($item->final_price);
            
        //     if ($item->isSpecialCase == 1) {
        //         $row['aksi']        = '<div class="btn-group">
        //             <button onclick="deleteData(`'. route('cart.destroy', $item->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus produk</button>
        //             <label class="switch">
        //             <input onclick="tes('. $item->id .', this);" id="'. $item->id .'"  checked="true" type="checkbox">
        //             <span class="slider round"></span>
        //             </label>
        //         </div>';            
            
        //     } else if($item->isSpecialCase == 0) {
        //         $row['aksi']        = '<div class="btn-group">
        //         <button onclick="deleteData(`'. route('cart.destroy', $item->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus produk</button>
        //         <label class="switch">
        //         <input onclick="tes('. $item->id .', this);" id="'. $item->id .'"   type="checkbox">
        //         <span class="slider round"></span>
        //         </label>
        //     </div>';   
        //     } else {
                
        //     }
            
            
        //     $data[] = $row;
        //     $total += $item->final_price;
        //     $total_item += $item->count;
        // }

        // $transaction->total_price = $total;
        // $transaction->update();

        // $data[] = [
        //     'sku' => '
        //         <div class="total hide">'. $total .'</div>
        //         <div class="total_item hide">'. $total_item .'</div>',
        //     'title' => '',
        //     'base_price'  => '',
        //     'count'      => '',
        //     'total_price'    => '',
        //     'final_price'    => '',
        //     'diskon'    => '',
        //     'subtotal'    => '',
        //     'aksi'        => '',
        // ];

        // return datatables()
        //     ->of($data)
        //     ->addIndexColumn()
        //     ->rawColumns(['aksi', 'sku', 'count', 'diskon'])
        //     ->make(true);
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

    public function show_cart($nomor_nota)
    {
        $detail = Cart::where('nomor_nota', $nomor_nota)->where('isSend', 0)->where('flag', 0)->get();
        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_id', function ($detail) {
                return $detail->produk->nama_produk ;
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
        $id_customer = $request->id_customer; //req
        // return $nomor_nota;
        $detail = Cart::find($id); //count
        $produk = ProdukNew::where('id', $detail->product_id)->first();
        
        $sku = $detail->produk->sku;
        $kategori = $detail->produk->kategori;
        $kondisi = $detail->produk->produk;
        
        $limit_1 = $produk->priceRules->limit_1;
        $limit_2 = $produk->priceRules->limit_2;

        if ($count == 0) {
            
            $detail->delete();

            return response()->json('Terhapus', 200);
        
        } else {

            if ($detail->member->customer_type == 3 || $detail->member->customer_type == 4) {
                
                $detail->count = $count;
                $detail->final_price = $detail->base_price * $count;
                $detail->update();
                // return $detail;
                return response()->json('Updated not enduser', 200);

            } else {
                
                if ($produk->kondisi == 'Seedling' || $produk->kondisi == 'Remaja' || $produk->kondisi == 'Botol') {
                    
                    $detail->count = $count;
                    $detail->update();
                    # code...
                    $cart = Cart::where('nomor_nota', $detail->nomor_nota)->get();
                    $seedling = 0;
                    $remaja = 0;
                    $botol = 0;

                    foreach ($cart as $key) {
                        if ($key->produk->kondisi == 'Seedling') {
                            $seedling += $key->count;
                        } elseif ($key->produk->kondisi == 'Remaja') {
                            $remaja += $key->count;
                        } elseif ($key->produk->kondisi == 'Botol') {
                            $botol += $key->count;
                        } else {
                        }
                    }

                    if ($produk->kondisi == 'Seedling') {
                                                //SEEDLING
                        $rules_s = PriceRules::where('kategori_produk', 'Seedling')->first();
                        $limits_1 = $rules_s->limit_1;
                        $limits_2 = $rules_s->limit_2;

                        if ($seedling < $limits_1) {
                            //HARGA 1
                            $cart_s_low = Cart::where('nomor_nota', $detail->nomor_nota)->get();

                            foreach ($cart_s_low as $low) {
                                $prod_s_low = $low->produk->kondisi;
                                
                                if ($prod_s_low == 'Seedling') {
                                    $price_s_low = Price::where('sku_produk', $low->produk->sku)->first();
                                    $low->base_price = $price_s_low->harga_1;
                                    $low->final_price = $detail->base_price * $count;
                                    $low->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 1', 200);

                        } elseif ($seedling >= $limits_1 && $seedling < $limits_2) {
                            //HARGA 2
                            $cart_s_mid = Cart::where('nomor_nota', $detail->nomor_nota)->get();

                            foreach ($cart_s_mid as $mid) {
                                $prod_s_mid = $mid->produk->kondisi;
                                
                                if ($prod_s_mid == 'Seedling') {
                                    $price_s_mid = Price::where('sku_produk', $mid->produk->sku)->first();
                                    $mid->base_price = $price_s_mid->harga_2;
                                    $mid->final_price = $detail->base_price * $count;
                                    $mid->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 2', 200);

                        } elseif ($seedling >= $limits_2) {
                            //HARGA 3
                            $cart_s_up = Cart::where('nomor_nota', $detail->nomor_nota)->get();

                            foreach ($cart_s_up as $up) {
                                $prod_s_up = $up->produk->kondisi;
                                
                                if ($prod_s_up == 'Seedling') {
                                    $price_s_up = Price::where('sku_produk', $up->produk->sku)->first();
                                    $up->base_price = $price_s_up->harga_3;
                                    $up->final_price = $detail->base_price * $count;
                                    $up->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 3', 200);
                        } else {
                        }

                    } elseif ($produk->kondisi == 'Remaja') {
                                                //REMAJA
                        // return response()->json('Updated without limit', 200);

                        $rules_r = PriceRules::where('kategori_produk', 'Remaja')->first();
                        $limits_1 = $rules_r->limit_1;
                        $limits_2 = $rules_r->limit_2;

                        if ($remaja < $limits_1) {
                            //HARGA 1
                            $cart_r_low = Cart::where('nomor_nota', $detail->nomor_nota)->get();

                            foreach ($cart_r_low as $low) {
                                $prod_r_low = $low->produk->kondisi;
                                
                                if ($prod_r_low == 'Remaja') {
                                    $price_r_low = Price::where('sku_produk', $low->produk->sku)->first();
                                    $low->base_price = $price_r_low->harga_1;
                                    $low->final_price = $detail->base_price * $count;
                                    $low->update();
                                } else {
                                }
                            }
                            return response()->json('Updated remaja with price 1', 200);

                        } elseif ($remaja >= $limits_1 && $remaja < $limits_2) {
                            //HARGA 2
                            $cart_r_mid = Cart::where('nomor_nota', $detail->nomor_nota)->get();

                            foreach ($cart_r_mid as $mid) {
                                $prod_r_mid = $mid->produk->kondisi;
                                
                                if ($prod_r_mid == 'Remaja') {
                                    $price_r_mid = Price::where('sku_produk', $mid->produk->sku)->first();
                                    $mid->base_price = $price_r_mid->harga_2;
                                    $mid->final_price = $detail->base_price * $count;
                                    $mid->update();
                                } else {
                                }
                            }
                            return response()->json('Updated remaja with price 2', 200);

                        } elseif ($remaja >= $limits_2) {
                            //HARGA 3
                            $cart_r_up = Cart::where('nomor_nota', $detail->nomor_nota)->get();

                            foreach ($cart_r_up as $up) {
                                $prod_r_up = $up->produk->kondisi;
                                
                                if ($prod_r_up == 'Remaja') {
                                    $price_r_up = Price::where('sku_produk', $up->produk->sku)->first();
                                    $up->base_price = $price_r_up->harga_3;
                                    $up->final_price = $detail->base_price * $count;
                                    $up->update();
                                } else {
                                }
                            }
                            return response()->json('Updated remaja with price 3', 200);
                        } else {
                        }

                    } elseif ($produk->kondisi == 'Botol') {
                                            //BOTOL
                        // return response()->json('Updated without limit', 200);

                        $rules_b = PriceRules::where('kategori_produk', 'Botol')->first();
                        $limits_1 = $rules_b->limit_1;
                        $limits_2 = $rules_b->limit_2;

                        if ($botol < $limits_1) {
                            //HARGA 1
                            $cart_b_low = Cart::where('nomor_nota', $detail->nomor_nota)->get();

                            foreach ($cart_b_low as $low) {
                                $prod_b_low = $low->produk->kondisi;
                                
                                if ($prod_b_low == 'Botol') {
                                    $price_b_low = Price::where('sku_produk', $low->produk->sku)->first();
                                    $low->base_price = $price_b_low->harga_1;
                                    $low->final_price = $detail->base_price * $count;
                                    $low->update();
                                } else {
                                }
                            }
                            return response()->json('Updated Botol with price 1', 200);

                        } elseif ($botol >= $limits_1 && $botol < $limits_2) {
                            //HARGA 2
                            $cart_b_mid = Cart::where('nomor_nota', $detail->nomor_nota)->get();

                            foreach ($cart_b_mid as $mid) {
                                $prod_b_mid = $mid->produk->kondisi;
                                
                                if ($prod_b_mid == 'Botol') {
                                    $price_b_mid = Price::where('sku_produk', $mid->produk->sku)->first();
                                    $mid->base_price = $price_b_mid->harga_2;
                                    $mid->final_price = $detail->base_price * $count;
                                    $mid->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 2', 200);

                        } elseif ($botol >= $limits_2) {
                            //HARGA 3
                            $cart_b_up = Cart::where('nomor_nota', $detail->nomor_nota)->get();

                            foreach ($cart_b_up as $up) {
                                $prod_b_up = $up->produk->kondisi;
                                
                                if ($prod_b_up == 'Botol') {
                                    $price_b_up = Price::where('sku_produk', $up->produk->sku)->first();
                                    $up->base_price = $price_b_up->harga_3;
                                    $up->final_price = $detail->base_price * $count;
                                    $up->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 3', 200);
                        } else {
                        }

                    } else {
                    }

                } else {
                    # exception
                    if ($produk->priceRules->limit_1 == 0) {

                        $detail->count = $count;
                        $detail->final_price = $detail->base_price * $count;
                        $detail->update();
        
                        return response()->json('Updated without limit', 200);
        
                    } else {   
                        $harga_1 = $produk->price->harga_1;
        
                        if ($harga_1 == 0) {
        
                            $detail->count = $count;
                            $detail->final_price = $detail->base_price * $count;
                            $detail->update();
        
                            return response()->json('Updated with limit but without price', 200);
        
                        } else {
                            // return $produk->price->harga_1;
        
                            if ($count < $limit_1) {
                                
                                $detail->count = $count;
                                $detail->base_price = $produk->price->harga_1;
                                $detail->final_price = $detail->base_price * $count;
                                $detail->update();
        
                                return response()->json('Updated with price 1', 200);
        
                            } elseif ($count >= $limit_1 && $count < $limit_2) {
                                
                                $detail->count = $count;
                                $detail->base_price = $produk->price->harga_2;
                                $detail->final_price = $detail->base_price * $count;
                                $detail->update();
        
                                return response()->json('Updated with price 2', 200);
        
                            } elseif ($count >= $limit_2) {
        
                                $detail->count = $count;
                                $detail->base_price = $produk->price->harga_3;
                                $detail->final_price = $detail->base_price * $count;
                                $detail->update();
        
                                return response()->json('Updated with price 3', 200);
                            }else {
                            }
                        }
                    }
                }
            }
        }
    }

    public function update_transaction(Request $request, $id)
    {
        $id = $request->id;
        $count = $request->jumlah; //req
        $detail = CartTransaction::find($id); //count

        $produk = ProdukNew::where('id', $detail->product_id)->first();
        $sku = $detail->produk->sku;
        $kategori = $detail->produk->kategori;
   
        $limit_1 = $produk->priceRules->limit_1;
        $limit_2 = $produk->priceRules->limit_2;
      

        if ($count == 0) {
            
            $detail->delete();

            return response()->json('Terhapus', 200);
        
        } else {
            if ($detail->member->customer_type == 3 || $detail->member->customer_type == 4) {
                
                $detail->count = $count;
                $detail->final_price = $detail->base_price * $count;
                $detail->update();

                return response()->json('Updated not enduser', 200);

            } else {
                
                if ($produk->kondisi == 'Seedling' || $produk->kondisi == 'Remaja' || $produk->kondisi == 'Botol') {
                    
                    $detail->count = $count;
                    $detail->update();
                    # code...
                    $cart = CartTransaction::where('transaction_id', $detail->transaction_id)->get();
                    $seedling = 0;
                    $remaja = 0;
                    $botol = 0;

                    foreach ($cart as $key) {
                        if ($key->produk->kondisi == 'Seedling') {
                            $seedling += $key->count;
                        } elseif ($key->produk->kondisi == 'Remaja') {
                            $remaja += $key->count;
                        } elseif ($key->produk->kondisi == 'Botol') {
                            $botol += $key->count;
                        } else {
                        }
                    }

                    if ($produk->kondisi == 'Seedling') {
                                                //SEEDLING
                        $rules_s = PriceRules::where('kategori_produk', 'Seedling')->first();
                        $limits_1 = $rules_s->limit_1;
                        $limits_2 = $rules_s->limit_2;

                        if ($seedling < $limits_1) {
                            //HARGA 1
                            $cart_s_low = CartTransaction::where('transaction_id', $detail->transaction_id)->get();

                            foreach ($cart_s_low as $low) {
                                $prod_s_low = $low->produk->kondisi;
                                
                                if ($prod_s_low == 'Seedling') {
                                    $price_s_low = Price::where('sku_produk', $low->produk->sku)->first();
                                    $low->base_price = $price_s_low->harga_1;
                                    $low->final_price = $detail->base_price * $count;
                                    $low->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 1', 200);

                        } elseif ($seedling >= $limits_1 && $seedling < $limits_2) {
                            //HARGA 2
                            $cart_s_mid = CartTransaction::where('transaction_id', $detail->transaction_id)->get();

                            foreach ($cart_s_mid as $mid) {
                                $prod_s_mid = $mid->produk->kondisi;
                                
                                if ($prod_s_mid == 'Seedling') {
                                    $price_s_mid = Price::where('sku_produk', $mid->produk->sku)->first();
                                    $mid->base_price = $price_s_mid->harga_2;
                                    $mid->final_price = $detail->base_price * $count;
                                    $mid->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 2', 200);

                        } elseif ($seedling >= $limits_2) {
                            //HARGA 3
                            $cart_s_up = CartTransaction::where('transaction_id', $detail->transaction_id)->get();

                            foreach ($cart_s_up as $up) {
                                $prod_s_up = $up->produk->kondisi;
                                
                                if ($prod_s_up == 'Seedling') {
                                    $price_s_up = Price::where('sku_produk', $up->produk->sku)->first();
                                    $up->base_price = $price_s_up->harga_3;
                                    $up->final_price = $detail->base_price * $count;
                                    $up->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 3', 200);
                        } else {
                        }

                    } elseif ($produk->kondisi == 'Remaja') {
                                                //REMAJA
                        // return response()->json('Updated without limit', 200);

                        $rules_r = PriceRules::where('kategori_produk', 'Remaja')->first();
                        $limits_1 = $rules_r->limit_1;
                        $limits_2 = $rules_r->limit_2;

                        if ($remaja < $limits_1) {
                            //HARGA 1
                            $cart_r_low = CartTransaction::where('transaction_id', $detail->transaction_id)->get();

                            foreach ($cart_r_low as $low) {
                                $prod_r_low = $low->produk->kondisi;
                                
                                if ($prod_r_low == 'Remaja') {
                                    $price_r_low = Price::where('sku_produk', $low->produk->sku)->first();
                                    $low->base_price = $price_r_low->harga_1;
                                    $low->final_price = $detail->base_price * $count;
                                    $low->update();
                                } else {
                                }
                            }
                            return response()->json('Updated remaja with price 1', 200);

                        } elseif ($remaja >= $limits_1 && $remaja < $limits_2) {
                            //HARGA 2
                            $cart_r_mid = CartTransaction::where('transaction_id', $detail->transaction_id)->get();

                            foreach ($cart_r_mid as $mid) {
                                $prod_r_mid = $mid->produk->kondisi;
                                
                                if ($prod_r_mid == 'Remaja') {
                                    $price_r_mid = Price::where('sku_produk', $mid->produk->sku)->first();
                                    $mid->base_price = $price_r_mid->harga_2;
                                    $mid->final_price = $detail->base_price * $count;
                                    $mid->update();
                                } else {
                                }
                            }
                            return response()->json('Updated remaja with price 2', 200);

                        } elseif ($remaja >= $limits_2) {
                            //HARGA 3
                            $cart_r_up = CartTransaction::where('transaction_id', $detail->transaction_id)->get();

                            foreach ($cart_r_up as $up) {
                                $prod_r_up = $up->produk->kondisi;
                                
                                if ($prod_r_up == 'Remaja') {
                                    $price_r_up = Price::where('sku_produk', $up->produk->sku)->first();
                                    $up->base_price = $price_r_up->harga_3;
                                    $up->final_price = $detail->base_price * $count;
                                    $up->update();
                                } else {
                                }
                            }
                            return response()->json('Updated remaja with price 3', 200);
                        } else {
                        }

                    } elseif ($produk->kondisi == 'Botol') {
                                            //BOTOL
                        // return response()->json('Updated without limit', 200);

                        $rules_b = PriceRules::where('kategori_produk', 'Botol')->first();
                        $limits_1 = $rules_b->limit_1;
                        $limits_2 = $rules_b->limit_2;

                        if ($botol < $limits_1) {
                            //HARGA 1
                            $cart_b_low = CartTransaction::where('transaction_id', $detail->transaction_id)->get();

                            foreach ($cart_b_low as $low) {
                                $prod_b_low = $low->produk->kondisi;
                                
                                if ($prod_b_low == 'Botol') {
                                    $price_b_low = Price::where('sku_produk', $low->produk->sku)->first();
                                    $low->base_price = $price_b_low->harga_1;
                                    $low->final_price = $detail->base_price * $count;
                                    $low->update();
                                } else {
                                }
                            }
                            return response()->json('Updated Botol with price 1', 200);

                        } elseif ($botol >= $limits_1 && $botol < $limits_2) {
                            //HARGA 2
                            $cart_b_mid = CartTransaction::where('transaction_id', $detail->transaction_id)->get();

                            foreach ($cart_b_mid as $mid) {
                                $prod_b_mid = $mid->produk->kondisi;
                                
                                if ($prod_b_mid == 'Botol') {
                                    $price_b_mid = Price::where('sku_produk', $mid->produk->sku)->first();
                                    $mid->base_price = $price_b_mid->harga_2;
                                    $mid->final_price = $detail->base_price * $count;
                                    $mid->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 2', 200);

                        } elseif ($botol >= $limits_2) {
                            //HARGA 3
                            $cart_b_up = CartTransaction::where('transaction_id', $detail->transaction_id)->get();

                            foreach ($cart_b_up as $up) {
                                $prod_b_up = $up->produk->kondisi;
                                
                                if ($prod_b_up == 'Botol') {
                                    $price_b_up = Price::where('sku_produk', $up->produk->sku)->first();
                                    $up->base_price = $price_b_up->harga_3;
                                    $up->final_price = $detail->base_price * $count;
                                    $up->update();
                                } else {
                                }
                            }
                            return response()->json('Updated seed with price 3', 200);
                        } else {
                        }

                    } else {
                    }

                } else {
                    # exception
                    if ($produk->priceRules->limit_1 == 0) {

                        $detail->count = $count;
                        $detail->final_price = $detail->base_price * $count;
                        $detail->update();
        
                        return response()->json('Updated without limit', 200);
        
                    } else {   
                        $harga_1 = $produk->price->harga_1;
        
                        if ($harga_1 == 0) {
        
                            $detail->count = $count;
                            $detail->final_price = $detail->base_price * $count;
                            $detail->update();
        
                            return response()->json('Updated with limit but without price', 200);
        
                        } else {
                            // return $produk->price->harga_1;
        
                            if ($count < $limit_1) {
                                
                                $detail->count = $count;
                                $detail->base_price = $produk->price->harga_1;
                                $detail->final_price = $detail->base_price * $count;
                                $detail->update();
        
                                return response()->json('Updated with price 1', 200);
        
                            } elseif ($count >= $limit_1 && $count < $limit_2) {
                                
                                $detail->count = $count;
                                $detail->base_price = $produk->price->harga_2;
                                $detail->final_price = $detail->base_price * $count;
                                $detail->update();
        
                                return response()->json('Updated with price 2', 200);
        
                            } elseif ($count >= $limit_2) {
        
                                $detail->count = $count;
                                $detail->base_price = $produk->price->harga_3;
                                $detail->final_price = $detail->base_price * $count;
                                $detail->update();
        
                                return response()->json('Updated with price 3', 200);
                            }else {
                            }
                        }
                    }
                }
            }
        }
    }

    public function update_price(Request $request, $id)
    {
        $id = $request->id;
        $base_price = $request->jumlah; //req
        $detail = Cart::find($id); //count

        $detail->base_price = $base_price;
        $detail->final_price = $detail->base_price * $detail->count;
        $detail->update();
        
        return response()->json('Updated', 200);
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
        $nomor_nota = $cart->nomor_nota;
        $cart->delete();
        
        $cek = Cart::where('nomor_nota', $nomor_nota)->first();
        if ($cek) {
            # code...
        } else {
            # code...
            $NoNota = NomorNota::where('nomor_nota', $nomor_nota)->first();
            $NoNota->status = 0;
            $NoNota->update();

            // session(['nomor_nota' => $nomor_nota]);

        }
    }

    public function cart_transaction_destroy($id)
    {
        
        $cart_transaction = CartTransaction::find($id);
        $cart = Cart::where('id', $cart_transaction->cart_id)->first();

        $cart->delete();
        $cart_transaction->delete();
        // $nomor_nota = $cart->nomor_nota;
        // $cek = Cart::where('nomor_nota', $nomor_nota)->first();
        // if ($cek) {
        //     # code...
        // } else {
        //     # code...
        //     $NoNota = NomorNota::where('nomor_nota', $nomor_nota)->first();
        //     $NoNota->status = 0;
        //     $NoNota->update();

        //     // session(['nomor_nota' => $nomor_nota]);
        // }
    }

    public function cancel( $id_product,$customer_id)
    {
        // $cart = Cart::find($id);
        $cart = Cart::where('customer_id', $customer_id)->where('product_id', $id_product)->where('isSend', 0)->where('flag', 0)->first();

        $cart->delete();

    }

    public function addProduct($id, $count, $customer_id){
        
        $id_customer = $customer_id;
        
        $member = Member::where('id', $id_customer)->first();
        $produk = ProdukNew::where('id', $id)->first();
        $nomor_nota = session('nomor_nota');
        // return $produk;
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $id = auth()->id();
        $detail = new Cart();
        $detail->employee_id = $id;
        $detail->nomor_nota = $nomor_nota;
        $detail->customer_id = $id_customer;
        $detail->product_id = $produk->id;
        // return $nomor_nota;
        // return $member->customer_type;

        // if ($member->customer_type == 1) {
        //     $detail->base_price = $produk->offline_price;
        // } else if ($member->customer_type == 2) {
        //     $detail->base_price = $produk->offline_price;
        // } else if ($member->customer_type == 3) {
        //     $detail->base_price = $produk->reseller_price;
        // } else if ($member->customer_type == 4) {
        //     $detail->base_price = $produk->agen_price;
        // } else {
        // }

        if ($member->customer_type == 1) {
            $detail->base_price = $produk->price->harga_1;
        } else if ($member->customer_type == 2) {
            $detail->base_price = $produk->price->harga_1;
        } else if ($member->customer_type == 3) {
            $detail->base_price = $produk->price->harga_2;
        } else if ($member->customer_type == 4) {
            $detail->base_price = $produk->price->harga_3;
        } else {
        }
        // return $produk->price->harga_1;

        // return $detail->base_price;
        
        $detail->count = $count;
        $detail->final_price = $detail->base_price * $detail->count;
        $detail->isSpecialCase = 0;
        $detail->isSend = 0;
        $detail->flag = 0;
        $detail->save();

        $NoNota = NomorNota::where('nomor_nota', $nomor_nota)->first();
        $NoNota->status = 1;
        $NoNota->update();

        return response()->json('Data berhasil ditambah', 200);
    
    }

    public function edit_addProduct($id, $count, $nomor_nota, $customer_id){
        
        $id_customer = $customer_id;
        
        $member = Member::where('id', $id_customer)->first();
        $produk = ProdukNew::where('id', $id)->first();

        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }


        $id = auth()->id();
        $detail = new Cart();
        $detail->employee_id = $id;
        $detail->nomor_nota = $nomor_nota;
        $detail->customer_id = $id_customer;
        $detail->product_id = $produk->id;
        
        // if ($member->customer_type == 1) {
        //     $detail->base_price = $produk->offline_price;
        // } else if ($member->customer_type == 2) {
        //     $detail->base_price = $produk->offline_price;
        // } else if ($member->customer_type == 3) {
        //     $detail->base_price = $produk->reseller_price;
        // } else if ($member->customer_type == 4) {
        //     $detail->base_price = $produk->agen_price;
        // } else {
        //     # code...
        // }

        if ($member->customer_type == 1) {
            $detail->base_price = $produk->price->harga_1;
        } else if ($member->customer_type == 2) {
            $detail->base_price = $produk->price->harga_1;
        } else if ($member->customer_type == 3) {
            $detail->base_price = $produk->price->harga_2;
        } else if ($member->customer_type == 4) {
            $detail->base_price = $produk->price->harga_3;
        } else {
        }

        $detail->count = $count;
        $detail->final_price = $detail->base_price * $detail->count;
        $detail->isSpecialCase = 0;
        $detail->isSend = 0;
        $detail->flag = 0;
        $detail->save();

        $NoNota = NomorNota::where('nomor_nota', $nomor_nota)->first();
        $NoNota->status = 1;
        $NoNota->update();

        return response()->json('Data berhasil ditambah', 200);
    }
   

    public function new()
    {
        $id_customer = session('nomor_nota');
        
        $carts = Cart::with('member')
                ->where('nomor_nota', $id_customer)
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
                return $carts->produk->nama_produk;
            })
            ->addColumn('jumlah', function ($carts) {
                return '<input type="number" class="form-control input-sm count" 
                data-id="'. $carts->id .'" 
                value="'. $carts->count .'">';
            })
            ->addColumn('harga', function ($carts) {
                return '<input type="number" class="form-control input-sm price" 
                data-id="'. $carts->id .'" 
                value="'. $carts->base_price .'">';
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
                    <label class="switch">
                        <input onclick="tes('. $carts->id .', this);" id="'. $carts->id .'"  checked="true" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                    ';
                } else {
                    return '
                    <div class="btn-group">
                        <button onclick="deleteData(`'. route('cart.destroy', $carts->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus</button>
                    </div>
                    ';
                }
            })
            ->addColumn('tambahan', function ($carts) {
                return '<input type="number" class="form-control input-sm discount" data-id="'. $carts->id .'" value="'. $carts->discount .'">';
            })
            ->rawColumns(['aksi', 'select_all', 'jumlah', 'tambahan', 'harga'])
            ->make(true);
    }

    // <label  class="switch">
    // <input onclick="tes('. $carts->id .', this);" id="'. $carts->id .'"  type="checkbox">
    // <span class="slider round"></span>
    // </label>

    public function purge_cart($nomor_nota)
    {
        $cart = Cart::where('nomor_nota', $nomor_nota)->where('isSend', 0)->where('flag', 0)->get();

        foreach ($cart as $item) {
            $item->delete();
    }
    return response()->json('Cart berhasil dihapus', 200);
}

    public function purge_send($id)
    {
        $cart = Cart::where('customer_id', $id)->where('isSend', 1)->where('flag', 0)->get();

        foreach ($cart as $item) {
            $item->delete();
    }
    return response()->json('Cart berhasil dihapus', 200);
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

    public function sess_name($id, $text)
    {
        $check_member = Member::where('id', $id)->first();
        if ($check_member) {

            session(['id_customer' => $id]);

            return response()->json([
                'customer_id' => $id,
                'success' => true
            ]);

        } else {
            
            $customers = new Member();
            $customers->name = $text;
            $customers->phone = 0;
            $customers->customer_type = 2;
            $customers->save();

            session(['id_customer' => $customers->id]);

            return response()->json([
                'customer_id' => $customers->id,
                'success' => true
            ]);

        }

        

    }

    public function loadType($id, $type)
    {
        $id_customer = session('id_customer');

        $check_member = Member::where('id', $id_customer)->first();

        if ($check_member) {

            $check_member->customer_type = $id;
            $check_member->update();

        } else {
            // $customers = new Member();
            // $customers->name = $text;
            // $customers->phone = 0;
            // $customers->customer_type = 2;
            // $customers->save();
        }

        return response()->json([
            'customer_id' => $id_customer,
            'success' => true
        ]);

    }

    public function loadForm($nomor_nota)
    {
        // var_dump($nomor_nota);
        $cart = Cart::where('nomor_nota', $nomor_nota)->get();
        $total_nominal = 0;
        $total_barang = 0;
        // var_dump($cart);
        if ($cart) {
            # code...
            foreach ($cart as $key) {
                # code...
                $total_nominal += $key->final_price;
                $total_barang += $key->count;
            }

            $data  = [
                'total_nominal' => format_uang($total_nominal),
                'total_barang' => $total_barang,
            ]; 

        } else {
            # code...
            $data  = [
                'total_nominal' => format_uang($total_nominal),
                'total_barang' => $total_barang,
            ];  
        }
        
        return response()->json($data);

    }

    public function transaction(Request $request)
    {
        // return $request->id[0];
        $id=0;
        $counter=0;
        $id_pembelian=0;
        // return $request->id;
        foreach ($request->id as $value) {
            
            $cart = Cart::where('nomor_nota', $value)->first();
            session(['nomor_nota_kasir' => $value]);

            $customer_id = $cart->customer_id;
            $employee_id = $cart->employee_id;

            if ($counter == 0) {
                # code...
                // $id = $customer_id;
                session(['customer_id' => $customer_id]);

                $x = auth()->id();
            
                $pembelian = new Pembelian();
                $pembelian->order_type          = 0;
                $pembelian->number_ref          = 0;
                $pembelian->customer_id         = $customer_id;
                $pembelian->total_price         = 0;
                $pembelian->total_payment       = 0;
                $pembelian->order_price         = 0;
                $pembelian->payment_method      = '';
                $pembelian->employee_id         = $x;
                $pembelian->transaction_status  = 0;
                $pembelian->invoice_status      = '';

                $pembelian->save();

                $id_pembelian = $pembelian->id;
                session(['transaction_id' => $pembelian->id]);
                session(['customer_id' => $pembelian->customer_id]);
                $counter++;
                // return $id_pembelian;

            } else {
                # code...
            }
            // return $pembelian;
            $count = 0;

            $detail = Cart::where('nomor_nota', $value)->where('flag',0)->get();
                
                foreach ($detail as $key) {
                    # code...
                    $cart_transaction = new CartTransaction();
                    $cart_transaction->transaction_id = $id_pembelian;
                    $cart_transaction->cart_id = $key->id;
                    $cart_transaction->employee_id = $key->employee_id;
                    $cart_transaction->customer_id = $key->customer_id;
                    $cart_transaction->product_id = $key->product_id;
                    $cart_transaction->base_price = $key->base_price;
                    $cart_transaction->final_price = $key->final_price;
                    $cart_transaction->count = $key->count;
                    $cart_transaction->discount = $key->discount;
                    $cart_transaction->save();
                    $count++;
                }
            
        }   
        return response('sukses');

    }

    public function transaction_detail()
    {
        
        $id_pembelian = session('transaction_id');
        $id_customer = session('customer_id');
        $produk = ProdukNew::where('enable', 1)->orderBy('nama_produk', 'asc')->get();
        $member = Member::find(session('customer_id'));
        $customerType = CustomerType::all();
        $customer = Member::all();
        $address = Address::all();
        // $address_book = AddressBook::all();
        $diskon = Diskon::select('name', 'discount')->where('status_discount', 1)->get();
        $nomor_nota='';
        $id_employee = auth()->id();
        $employee = User::find($id_employee);

        $transaction = Pembelian::find(session('transaction_id'));
        
        if ($transaction) {
            $total=0;
            $total_item=0;
    
            // $carts = Cart::where('isSend', 1)->where('flag', 0)->where('customer_id', session('customer_id'))->get();
            $carts = CartTransaction::where('transaction_id', $id_pembelian)->where('status', 0)->get();
            // return $carts;
            foreach ($carts as $key) {
                # code...
                $cek = Cart::where('id', $key->cart_id)->first();
                $nomor_nota = $cek->nomor_nota;
                break;
            }
            // return $nomor_nota;
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
            $transaction2 = Pembelian::where('order_type', 0)->where('customer_id', $id_customer)->first();
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
        return view('cart.transaction_detail', compact('id_pembelian', 'id_customer', 'produk', 'member', 'carts', 'total', 'transaction', 'diskon', 'customerType', 'customer', 'address', 'nomor_nota', 'employee'));
        
        //address_book ojok lali
    }

    public function transaction_data($id)
    {
        $transaction = Pembelian::where('id', $id)->first();
        
        $detail = CartTransaction::where('transaction_id', $id)->where('status',0)->get();
        $data = array();
        $total = 0;
        $total_item = 0;     

        foreach ($detail as $item) {
            
            $row = array();
            $row['sku'] = '<span class="label label-success">'. $item->produk['sku'] .'</span';
            $row['title'] = $item->produk['nama_produk'];
            $row['base_price']  = 'Rp. '. format_uang($item->base_price);
            $row['count']      = '<input type="number" class="form-control input-sm count" data-id="'. $item->id .'" value="'. $item->count .'">';
            $row['total_price']    = 'Rp. '. format_uang($item->final_price);
            $row['final_price']    = 'Rp. '. format_uang($item->final_price);
            $row['diskon']      = '<input type="number" class="form-control input-sm discount" data-id="'. $item->id .'" value="'. $item->discount .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->final_price);
            $row['aksi']        = '<div class="btn-group">
                    <button onclick="deleteData(`'. route('cart.cart_transaction_destroy', $item->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i></button>
                </div>';

            // if ($item->isSpecialCase == 1) {
            //     $row['aksi']        = '<div class="btn-group">
            //         <button onclick="deleteData(`'. route('cart.cart_transaction_destroy', $item->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus produk</button>
            //         <label class="switch">
            //         <input onclick="tes('. $item->id .', this);" id="'. $item->id .'"  checked="true" type="checkbox">
            //         <span class="slider round"></span>
            //         </label>
            //     </div>';            
            
            // } else if($item->isSpecialCase == 0) {
            //     $row['aksi']        = '<div class="btn-group">
            //     <button onclick="deleteData(`'. route('cart.cart_transaction_destroy', $item->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus produk</button>
            //     <label class="switch">
            //     <input onclick="tes('. $item->id .', this);" id="'. $item->id .'"   type="checkbox">
            //     <span class="slider round"></span>
            //     </label>
            // </div>';   
            // } else {
                
            // }
            
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

    public function transaction_store(Request $request)
    {
        $employee = auth()->user()->id;
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
        if ($request->created_at) {
            # code...
            $pembelian->created_at = $request->created_at;
        } else {
            # code...
        }
        
        $pembelian->transaction_status = 1;
        $pembelian->discount = $request->diskon;
        $pembelian->order_type = $request->order_type;
        $pembelian->order_price = $request->order_price;
        $pembelian->transfer_date = $request->transfer_date;
        $pembelian->name_sender = $request->name_sender;
        $pembelian->catatan = $request->catatan; 
        $pembelian->customer_type = $member->type->role; 


        $pembelian->total_price = $request->total_price;
        $pembelian->total_payment = (int)$request->total_price + (int)$request->order_price;
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

        $carts = CartTransaction::where('status', 0)
        ->where('transaction_id', $pembelian->id)
        ->get();

        foreach ($carts as $cart) {
            
            $list_product_transaction = new ListProductTransaction();
            $list_product_transaction->employee_id = $employee;
            $list_product_transaction->cart_id = $cart->cart_id;
            $list_product_transaction->customer_id = $cart->customer_id;
            $list_product_transaction->customer_type = $pembelian->customer_type;
            $list_product_transaction->transaction_id = $pembelian->id;
            $list_product_transaction->number_ref = $pembelian->number_ref;
            $list_product_transaction->product_id = $cart->product_id;
            
            $x = $cart->isSpecialCase;

            if ($x == 1) {
                $list_product_transaction->base_price = ($cart->base_price + $cart->discount) / $cart->count;
            } else {
                $list_product_transaction->base_price = $cart->base_price;
            }

            if ($request->order_type == 2) {
                $list_product_transaction->delivery_status = 0; 
            } else {
                # code...
            }
                   
            $list_product_transaction->final_price = $cart->final_price;
            $list_product_transaction->count = $cart->count;
            $list_product_transaction->isSpecialCase = $cart->isSpecialCase;
            $list_product_transaction->discount = $cart->discount;
            $list_product_transaction->handling_fee = 0; 
            $list_product_transaction->kategori = $cart->produk->kategori;   
            $list_product_transaction->payment_type = $request->payment_method;
            $list_product_transaction->created_at = $pembelian->created_at;
            $list_product_transaction->stock_status = 0; 

            $list_product_transaction->save();

            $total_price += $cart->final_price;

            $update_cart = Cart::where('id', $cart->cart_id)->first();
            // $update_cart->flag = 1;
            // $update_cart->update();

            $update_nomor_nota = Cart::where('nomor_nota', $update_cart->nomor_nota)->get();
            
            foreach ($update_nomor_nota as $value) {
                $value->flag = 1;
                $value->update();
            }
        }
        
        $pembelian->total_price = $total_price;
        $pembelian->update();
        
        session(['total' => $total]);
        session(['diskon' => $diskon]);
        session(['bayar' => $bayar]);

        return redirect()->route('pembelian.selesai');
    }

    public function destroy_session()
    {
        # code...
        // unset($_SESSION["id_customer"]);
        // $x = session('customer_id');
        session()->forget('customer_id');
        $member = Member::find(session('customer_id'));

        return response($member);
    }
}


