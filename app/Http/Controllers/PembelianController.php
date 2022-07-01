<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\ListProductTransaction;

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

    public function data()
    {
        $penjualan = Penjualan::orderBy('id', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('customer', function ($penjualan) {
                return $penjualan->member->name;
            })
            ->addColumn('number', function ($penjualan) {
                return format_uang($penjualan->number);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->total_price);
            })
            ->editColumn('payment_method', function ($penjualan) {
                return $penjualan->payment_method;
            })
            ->addColumn('employee_id', function ($penjualan) {
                return $penjualan->user->name;
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('pembelian.show', $penjualan->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('pembelian.destroy', $penjualan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create($id)
    {
        $cart    = Cart::select('id', 'employee_id')->where('customer_id', $id)->get();
        $x = auth()->id();
        $pembelian = new Penjualan();
        $pembelian->customer_id         = $id;
        $pembelian->employee_id         = $x;
        $pembelian->number              = 0;
        $pembelian->total_price         = 0;
        $pembelian->order_price         = 0;
        $pembelian->payment_status      = '';
        $pembelian->payment_method      = '';
        $pembelian->order_status        = '';
        $pembelian->isSell              = 0;

        $pembelian->save();

        session(['transaction_id' => $pembelian->id]);
        session(['customer_id' => $pembelian->customer_id]);

        return redirect()->route('pembelian_detail.index');

    }

    public function store(Request $request)
    {
        $pembelian = Penjualan::findOrFail($request->id_pembelian);
        $pembelian->number = $request->number;
        $pembelian->total_price = $request->total_price;
        $pembelian->payment_status = $request->payment_status;
        $pembelian->payment_method = $request->payment_method;
        $pembelian->order_status = $request->order_status;
        $pembelian->order_price = $request->order_price;
        $pembelian->isSell = $request->isSell;
        $pembelian->update();
        
        $bayar = $request->bayar;
        $total=$request->total;
        $diskon=$request->diskon;
        
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
                return '<span class="label label-success">'. $detail->produk->id .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->title;
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->count);
            })
            ->addColumn('total_price', function ($detail) {
                return 'Rp. '. format_uang($detail->base_price * $detail->count);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $pembelian = Penjualan::find($id);
        $detail    = ListProductTransaction::where('transaction_id', $pembelian->id)->get();
        
        foreach ($detail as $item) {
            $produk = Produk::find($item->id);
            if ($produk) {
                $produk->stock -= $item->count;
                $produk->update();
            }
            $item->delete();
        }

        $pembelian->delete();

        return response(null, 204);
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

        $detail = Cart::
        where('flag', 1)
        ->where('isSend', 1)
        ->where('customer_id', $customer->id)                        
        ->get();

       
        return view('pembelian.nota_kecil', compact('detail', 'transaksi', 'customer','total','diskon', 'bayar'));

    }
}
