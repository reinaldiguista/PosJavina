<?php

namespace App\Http\Controllers;

use App\Exports\ExportListProductTransaction;
use App\Models\Cart;
use App\Models\ListProductTransaction;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;


class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function harian_index(Request $request)
    {
        
        if ($request->tanggal_akhir) {
            $tanggalAkhir = $request->tanggal_akhir;
        } else {
            # code...
            $tanggalAkhir = date('Y-m-d');
        }
        

        return view('laporan.harian_index', compact('tanggalAkhir'));
    }

    public function detail_day(Request $request)
    {
        
        if ($request->tanggal_akhir) {
            $tanggalAkhir = $request->tanggal_akhir;
        } else {
            # code...
            $tanggalAkhir = date('Y-m-d');
        }
        

        return view('laporan.detail_day', compact('tanggalAkhir'));
    }

    public function day_payment(Request $request)
    {
        
        if ($request->tanggal_akhir) {
            $tanggalAkhir = $request->tanggal_akhir;
        } else {
            # code...
            $tanggalAkhir = date('Y-m-d');
        }
        

        return view('laporan.day_payment', compact('tanggalAkhir'));
    }

    public function day_order(Request $request)
    {
        
        if ($request->tanggal_akhir) {
            $tanggalAkhir = $request->tanggal_akhir;
        } else {
            # code...
            $tanggalAkhir = date('Y-m-d');
        }
        

        return view('laporan.day_order', compact('tanggalAkhir'));
    }

    public function day_cart(Request $request)
    {
        
        if ($request->tanggal_akhir) {
            $tanggalAkhir = $request->tanggal_akhir;
        } else {
            # code...
            $tanggalAkhir = date('Y-m-d');
        }
        

        return view('laporan.day_cart', compact('tanggalAkhir'));
    }

    public function getData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('total_payment');
            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->sum('total_payment');

            $pendapatan = $total_penjualan - $total_pembelian;
            $total_pendapatan += $total_penjualan;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_uang($total_penjualan);
            // $row['pembelian'] = format_uang($total_pembelian);
            // $row['pengeluaran'] = format_uang($total_pengeluaran);
            // $row['pendapatan'] = format_uang($pendapatan);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => 'Total Keseluruhan',
            'penjualan' => format_uang($total_pendapatan),
            // 'pembelian' => format_uang($total_pendapatan),
            // 'pengeluaran' => 'Total Pendapatan',
            // 'pendapatan' => format_uang($total_pendapatan),
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function data_harian($tanggal)
    {
        $data = $this->getHarian($tanggal);

        return datatables()
            ->of($data)
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function detail_day_data($tanggal)
    {
        $data = $this->report($tanggal);

        return datatables()
            ->of($data)
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function detail_day_cart($tanggal)
    {
        $data = $this->day_cart_data($tanggal);

        return datatables()
            ->of($data)
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function data_day_payment($tanggal)
    {
        $data = $this->day_report_payment($tanggal);

        return datatables()
            ->of($data)
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function day_report_payment($tanggal)
    {
        # code...
        $no = 1;
        $data = array();
        $total_pendapatan = 0;
        $total_transaksi = 0;

        // $transaction = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->where('transaction_status', '<', 2)->get();
        $transaction = Pembelian::select('payment_method')
        ->where('created_at', 'LIKE', "%$tanggal%")
        // ->distinct('payment_method')
        ->groupBy('payment_method')
        ->get();
        // return $transaction;

        foreach ($transaction as $key) {
            // $x = $key->payment_method;
            $loop = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->where('payment_method' , $key->payment_method)->get();
            $jumlah = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->where('payment_method' , $key->payment_method)->count();

            $total_pay = 0;

            foreach ($loop as $value) {
                # code...
                $payment = $value->total_payment;
                $total_pay += $payment;
            }
            
            
            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['payment_method'] = $key->payment_method;
            $row['jumlah_transaksi'] = $jumlah;
            $row['total'] = format_uang($total_pay)  ;
            $data[] = $row;

            $total_pendapatan += $total_pay;
            $total_transaksi += $jumlah;

            $total_pay = 0;
        }

        $data[] = [
            'DT_RowIndex' => 'Total',
            'payment_method' => '',
            'jumlah_transaksi' => $total_transaksi,
            'total' => format_uang($total_pendapatan),
        ];

        return $data;
    }

    public function data_day_order($tanggal)
    {
        $data = $this->day_report_order($tanggal);

        return datatables()
            ->of($data)
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function day_report_order($tanggal)
    {
        # code...
        $no = 1;
        $data = array();
        $total_pendapatan = 0;
        $total_barang = 0;

        $transaction = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->where('transaction_status', '<', 2)->orderBy('payment_method', 'asc')->get();
        // $transaction = Pembelian::select('number_ref')
        // ->where('created_at', 'LIKE', "%$tanggal%")
        // ->groupBy('number_ref')
        // ->get();

        foreach ($transaction as $key) {
            
            
            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['employee'] = $key->user->name;
            $row['number_ref'] = $key->number_ref;
            $row['customer'] = $key->member->name;
            $row['ongkir'] = $key->order_price + $key->order_price_kurir;
            $row['total'] = format_uang($key->total_payment)  ;
            $row['payment_method'] = $key->payment_method ;

            $data[] = $row;

            $total_pendapatan += $key->total_payment;
        }

        $data[] = [
            'DT_RowIndex' => 'Total',
            'employee' => '',
            'number_ref' => '',
            'customer' => '',
            'ongkir' => '',
            'total' => format_uang($total_pendapatan),
            'payment_method' => '',
        ];

        return $data;
    }

    public function getHarian($tanggal)
    {
        $no = 1;
        $data = array();
        $total_pendapatan = 0;
        $total_barang = 0;
        $today = date("dmY");                           // 20010310
        $ldate = date('Y-m-d');

        if ($tanggal) {
            # code...
            $penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->orderBy('payment_method', 'desc')->where('payment_method', 'not LIKE', "%Invoice%")->get();
        } else {
            # code...
            $penjualan = Penjualan::where('created_at', 'LIKE', "%$ldate%")->orderBy('payment_method', 'desc')->where('payment_method', 'not LIKE', "%Invoice%")->get();
        }
        // $penjualan = Penjualan::where('created_at', 'LIKE', "%$ldate%")->get();

        foreach ($penjualan as $item) {
            $detail = ListProductTransaction::where('transaction_id', $item->id)->sum('final_price');
            $barang = ListProductTransaction::where('transaction_id', $item->id)->sum('count');

            $total_pendapatan += $detail;  
            $total_barang += $barang;        
      
            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['number_ref'] = $item->number_ref;
            if ($item->order_type == 1) {
                $row['order_type'] = "Offline";
            } else {
                $row['order_type'] = "Online";
            }
            
            $row['payment_method'] = $item->payment_method;
            $row['penjualan'] = $detail;
            $row['barang'] = $barang;
            $row['aksi']        = '<div class="btn-group">
                                    <button style="margin-top: 5px" onclick="showDetail(`'. route('pembelian.show', $item->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-eye"> Detail</i></button>
                                    <br>                                
                                    </div>';
            $row['sender'] = $item->name_sender;

            $data[] = $row;
        }
    

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => 'Total Keseluruhan',
            'number_ref' => '',
            'order_type' => '',
            'payment_method' => '',
            'penjualan' => format_uang($total_pendapatan),
            'barang' => format_uang($total_barang),
            'aksi'        => '',
            'sender'        => '',


        ];

        return $data;
            
    }

    

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');
        
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }

    public function report($tanggal)
    {
        $no = 1;
        $data = array();
        $total_pendapatan = 0;
        $total_barang = 0;
        $today = date("dmY");                           // 20010310
        $ldate = date('Y-m-d');
        $counter = 0;
        // if ($tanggal) {
        //     $penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->orderBy('payment_method', 'desc')->get();
            
        //     $LSP = ListProductTransaction::where('created_at', 'LIKE', "%$tanggal%")->distinct('product_id')->get();
            
        // } else {
        //     $penjualan = Penjualan::where('created_at', 'LIKE', "%$ldate%")->orderBy('payment_method', 'desc')->get();
            
        //     $LSP = ListProductTransaction::where('created_at', 'LIKE', "%$ldate%")->distinct('product_id')->get();
        // }
        
        $transaction = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->where('transaction_status', '<', 2)->get();
        
        foreach ($transaction as $key) {

            $LSP = ListProductTransaction::where('transaction_id', $key->id)->get();
    
            foreach ($LSP as $item) {
    
                $detail = $item->final_price;
                $barang = $item->count;
    
                $total_pendapatan += $detail;  
                $total_barang += $barang;        
                
                if ($counter == 0) {
                    # code...
                    $row = array();
                    // $row['DT_RowIndex'] = $no++;
                    // $row['tanggal'] = tanggal_indonesia($tanggal, false);
                    $row['created_at'] = tanggal_indonesia($item->created_at, false);
                    $row['transaction'] = $item->transaction->number_ref;
                    $row['customer_type'] = $item->customer_type;
                    $row['customer'] = $item->member->name;
                    $row['product_sku'] = $item->produk->sku;
                    $row['product_title'] = $item->produk->title;
                    $row['category'] = $item->kategori;
                    $row['supplier'] = $item->produk->supplier;
                    $row['base_price'] = $item->base_price;
                    $row['count'] = $item->count;
                    $row['discount'] = $item->discount;
                    $row['tambahan'] = $item->tambahan;
                    $row['grand_discount'] = $key->discount;
                    $row['final_price'] = $item->final_price;
                    $row['hpp'] = $item->produk->hpp;
                    $row['catatan'] = $key->catatan;
                    $row['payment_method'] = $key->payment_method;
                    $row['ongkir'] = $key->order_price + $key->order_price_kurir;
                    // $row['payment_method'] = $key->payment_method;
                    
                    if ($item->order_type == 1) {
                        # code...
                        $row['tipe_transaksi'] = "Offline";
    
                    } else {
                        # code...
                        $row['tipe_transaksi'] = "Online";
                    }
                    $row['no_hp'] = $item->member->phone;
                    $row['kasir'] = $key->user->name;
                    $row['nomor_nota'] = $item->cart->nomor_nota;

                    $data[] = $row;

                    $counter++;

                } else {
                    # code...
                    $row = array();
                    // $row['DT_RowIndex'] = $no++;
                    // $row['tanggal'] = tanggal_indonesia($tanggal, false);
                    $row['created_at'] = tanggal_indonesia($item->created_at, false);
                    $row['transaction'] = "-";
                    $row['customer_type'] = $item->customer_type;
                    $row['customer'] = $item->member->name;
                    $row['product_sku'] = $item->produk->sku;
                    $row['product_title'] = $item->produk->title;
                    $row['category'] = $item->kategori;
                    $row['supplier'] = $item->produk->supplier;
                    $row['base_price'] = $item->base_price;
                    $row['count'] = $item->count;
                    $row['discount'] = $item->discount;
                    $row['tambahan'] = $item->tambahan;
                    $row['grand_discount'] = "-";
                    $row['final_price'] = $item->final_price;
                    $row['hpp'] = $item->produk->hpp;
                    $row['catatan'] = "-";
                    $row['payment_method'] = $key->payment_method;
                    $row['ongkir'] = '-';
                    
                    if ($item->order_type == 1) {
                        # code...
                        $row['tipe_transaksi'] = "Offline";
    
                    } else {
                        # code...
                        $row['tipe_transaksi'] = "Online";
                    }
                    $row['no_hp'] = "-";
                    $row['kasir'] = "-";
                    $row['nomor_nota'] = "-";


                    $data[] = $row;
                }

            }

            $counter = 0;
        }
        

        $data[] = [ 
            // 'DT_RowIndex' => 'Total Keseluruhan',
            'created_at' => 'Total Keseluruhan',
            'transaction' => '',
            'customer_type' => '',
            'customer' => '',
            'product_sku' => '',
            'product_title' => '',
            'category' => '',
            'supplier' => '',
            'base_price' => '',
            'count' => format_uang($total_barang),
            'discount' => '',
            'tambahan' => '',
            'grand_discount' => '',
            'final_price' => format_uang($total_pendapatan),
            'hpp' => '',
            'catatan' => '',
            'payment_method' => '',
            'ongkir' => '',
            'tipe_transaksi' => '',
            'no_hp' => '',
            'kasir' => '',
            'nomor_nota' => '',

        ];

        return $data;

    }

    public function day_cart_data($tanggal)
    {
        $no = 1;
        $data = array();
        $total_pendapatan = 0;
        $total_barang = 0;
        $today = date("dmY");                           // 20010310
        $ldate = date('Y-m-d');
        $counter = 0;
        // if ($tanggal) {
        //     $penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->orderBy('payment_method', 'desc')->get();
            
        //     $LSP = ListProductTransaction::where('created_at', 'LIKE', "%$tanggal%")->distinct('product_id')->get();
            
        // } else {
        //     $penjualan = Penjualan::where('created_at', 'LIKE', "%$ldate%")->orderBy('payment_method', 'desc')->get();
            
        //     $LSP = ListProductTransaction::where('created_at', 'LIKE', "%$ldate%")->distinct('product_id')->get();
        // }

        // $cart = Cart::whereDate('created_at', Carbon::today())->distinct('nomor_nota')->orderBy('id', 'desc')->first();
        
        // $cart = Cart::where('created_at', 'LIKE', "%$tanggal%")->orderBy('id', 'desc')->get();
        $nomor_nota = Cart::select('nomor_nota')->where('created_at', 'LIKE', "%$tanggal%")->groupBy('nomor_nota')->get();

        // return($nomor_nota);
                
        foreach ($nomor_nota as $key) {

            $cart = Cart::where('nomor_nota', $key->nomor_nota)->get();
    
            foreach ($cart as $item) {
                // return $item;
                
                $detail = $item->final_price;
                $barang = $item->count;
    
                $total_pendapatan += $detail;  
                $total_barang += $barang;        
                
                if ($counter == 0) {
                    # code...
                    $row = array();
                    // $row['DT_RowIndex'] = $no++;
                    // $row['tanggal'] = tanggal_indonesia($tanggal, false);
                    $row['created_at'] = tanggal_indonesia($item->created_at, false);
                    $row['nomor_nota'] = $item->nomor_nota;

                     if ($item->member->customer_type == 1) {
                         $row['customer_type'] = "Online";
                        } else if ($item->member->customer_type == 2) {
                        $row['customer_type'] = "Offline";
                    } else if ($item->member->customer_type == 3) {
                        $row['customer_type'] = "Reseller";
                    } else if ($item->member->customer_type == 4) {
                        $row['customer_type'] = "Agen";
                    } else {
                    }

                    $row['customer'] = $item->member->name;
                    $row['product_sku'] = $item->produk->sku;
                    $row['product_title'] = $item->produk->title;
                    $row['category'] = $item->produk->kategori;
                    $row['supplier'] = $item->produk->supplier;
                    $row['base_price'] = $item->base_price;
                    $row['count'] = $item->count;
                    $row['discount'] = $item->discount;
                    $row['tambahan'] = $item->tambahan;
                    $row['grand_discount'] = $key->discount;
                    $row['final_price'] = $item->final_price;
                    $row['hpp'] = $item->produk->hpp;
                    $row['no_hp'] = $item->member->phone;
                    $row['pegawai'] = $item->user->name;

                    $data[] = $row;

                    $counter++;

                } else {
                    # code...
                    $row = array();
                    // $row['DT_RowIndex'] = $no++;
                    // $row['tanggal'] = tanggal_indonesia($tanggal, false);
                    $row['created_at'] = tanggal_indonesia($item->created_at, false);
                    $row['nomor_nota'] = $item->nomor_nota;

                     if ($item->member->customer_type == 1) {
                         $row['customer_type'] = "Online";
                        } else if ($item->member->customer_type == 2) {
                        $row['customer_type'] = "Offline";
                    } else if ($item->member->customer_type == 3) {
                        $row['customer_type'] = "Reseller";
                    } else if ($item->member->customer_type == 4) {
                        $row['customer_type'] = "Agen";
                    } else {
                    }

                    $row['customer'] = $item->member->name;
                    $row['product_sku'] = $item->produk->sku;
                    $row['product_title'] = $item->produk->title;
                    $row['category'] = $item->produk->kategori;
                    $row['supplier'] = $item->produk->supplier;
                    $row['base_price'] = $item->base_price;
                    $row['count'] = $item->count;
                    $row['discount'] = $item->discount;
                    $row['tambahan'] = $item->tambahan;
                    $row['grand_discount'] = $key->discount;
                    $row['final_price'] = $item->final_price;
                    $row['hpp'] = $item->produk->hpp;
                    $row['no_hp'] = $item->member->phone;
                    $row['pegawai'] = $item->user->name;

                    $data[] = $row;
                }

            }

            $counter = 0;
        }
        

        $data[] = [ 
            // 'DT_RowIndex' => 'Total Keseluruhan',
            'created_at' => 'Total Keseluruhan',
            'nomor_nota' => '',
            'customer_type' => '',
            'customer' => '',
            'product_sku' => '',
            'product_title' => '',
            'category' => '',
            'supplier' => '',
            'base_price' => '',
            'count' => format_uang($total_barang),
            'discount' => '',
            'tambahan' => '',
            'grand_discount' => '',
            'final_price' => format_uang($total_pendapatan),
            'hpp' => '',
            'no_hp' => '',
            'pegawai' => '',

        ];

        return $data;

    }
    
    public function exportListTransaction($tanggal){
        $data = $this->report($tanggal);
        $pdf  = PDF::loadView('laporan.report', compact('tanggal','data'));
        $pdf->setPaper('a4', 'potrait');
        
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    
        // return Excel::download(new ExportListProductTransaction, 'list_transaction.xlsx');
    }


    public function data_byProduct($tanggal)
    {
        $data = $this->getHarian($tanggal);

        return datatables()
            ->of($data)
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function getByProduct($tanggal)
    {
        $no = 1;
        $data = array();
        $total_pendapatan = 0;
        $total_barang = 0;
        $today = date("dmY");                           // 20010310
        $ldate = date('Y-m-d');

        if ($tanggal) {
            # code...
            $penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->orderBy('payment_method', 'desc')->where('payment_method', 'not LIKE', "%Invoice%")->get();
            $LSP = ListProductTransaction::where('created_at', 'LIKE', "%$tanggal%")->groupBy('product_id')->get();

        } else {
            # code...
            $penjualan = Penjualan::where('created_at', 'LIKE', "%$ldate%")->orderBy('payment_method', 'desc')->where('payment_method', 'not LIKE', "%Invoice%")->get();
            $LSP = ListProductTransaction::where('created_at', 'LIKE', "%$ldate%")->groupBy('product_id')->get();

        }
        // $penjualan = Penjualan::where('created_at', 'LIKE', "%$ldate%")->get();

        foreach ($LSP as $item) {
            
            $detail = ListProductTransaction::where('transaction_id', $item->id)->sum('final_price');
            $barang = ListProductTransaction::where('transaction_id', $item->id)->sum('count');

            $total_pendapatan += $detail;  
            $total_barang += $barang;        
      
            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['number_ref'] = $item->number_ref;
            if ($item->order_type == 1) {
                $row['order_type'] = "Offline";
            } else {
                $row['order_type'] = "Online";
            }
            
            $row['payment_method'] = $item->payment_method;
            $row['penjualan'] = $detail;
            $row['barang'] = $barang;
            $row['aksi']        = '<div class="btn-group">
                                    <button style="margin-top: 5px" onclick="showDetail(`'. route('pembelian.show', $item->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-eye"> Detail</i></button>
                                    <br>                                
                                    </div>';
            
            $data[] = $row;
        }
    

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => 'Total Keseluruhan',
            'number_ref' => '',
            'order_type' => '',
            'payment_method' => '',
            'penjualan' => format_uang($total_pendapatan),
            'barang' => format_uang($total_barang),
            'aksi'        => '',

        ];

        return $data;
            
    }
}
