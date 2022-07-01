<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
        $penjualan = Penjualan::with('member')
        ->orderBy('id', 'desc')
        ->where('payment_status', 1)
        ->get();
            // dd ($penjualan);
        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            // ->addColumn('tanggal', function ($penjualan) {
            //      return tanggal_indonesia($penjualan->created_at, false);
            // })
            ->editColumn('kasir', function ($penjualan) {
                // return $penjualan->user->name ?? '';
                return $penjualan->employee_id;
            })
            ->addColumn('id_member', function ($penjualan) {
            //     // $member = $penjualan->member->id ?? '';
            //     // return '<span class="label label-success">'. $member .'</spa>';
                 return $penjualan->customer_id;
             })
            ->addColumn('total_price', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->total_price);
            })
            ->addColumn('payment_method', function ($penjualan) {
                return $penjualan->payment_method;
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create($id)
    {
        $penjualan = new Penjualan();
        $penjualan->customer_id = $id;
        $penjualan->number = 0;
        $penjualan->order_price = 0;
        $penjualan->order_status = "offline";
        $penjualan->employee_id = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index' , $penjualan->customer_id);
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan->id_member = $request->id_member;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->update();

        // $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        // foreach ($detail as $item) {
        //     $item->diskon = $request->diskon;
        //     $item->update();

        //     $produk = Produk::find($item->id_produk);
        //     $produk->stok -= $item->jumlah;
        //     $produk->update();
        // }

        return redirect()->route('transaksi.selesai');
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('transaction_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                // return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
                return $detail->product_id;
            })
            // ->addColumn('nama_produk', function ($detail) {
            //     return $detail->produk->nama_produk;
            // })
            ->addColumn('base_price', function ($detail) {
                return 'Rp. '. format_uang($detail->base_price);
            })
            ->addColumn('count', function ($detail) {
                return format_uang($detail->count);
            })
            ->addColumn('subtotal', function ($detail) {
                $x = $detail->base_price;
                $y = $detail->count;
                $subtotal = $x * $y;
                return 'Rp. '. format_uang($subtotal);
            })
            // ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('penjualan.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();
        
        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    // public function notaBesar()
    // {
    //     $setting = Setting::first();
    //     $penjualan = Penjualan::find(session('id_penjualan'));
    //     if (! $penjualan) {
    //         abort(404);
    //     }
    //     $detail = PenjualanDetail::with('produk')
    //         ->where('id_penjualan', session('id_penjualan'))
    //         ->get();

    //     $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
    //     $pdf->setPaper(0,0,609,440, 'potrait');
    //     return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    // }
}
