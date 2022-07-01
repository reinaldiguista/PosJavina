<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ListProductTransaction;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Supplier;

use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $id_pembelian = session('transaction_id');
        $id_customer = session('customer_id');
        $produk = Produk::orderBy('title')->get();
        $member = Member::find(session('customer_id'));
        $transaction = Penjualan::find(session('transaction_id'));
        $total=0;
        $total_item=0;

        $carts = Cart::where('customer_id', session('customer_id'))->get();
        foreach ($carts as $cart) {

            $product = Produk::find($cart->product_id);
            $list_product_transaction = new ListProductTransaction();
            $list_product_transaction->transaction_id = $id_pembelian;


            $list_product_transaction->product_id = $cart->product_id;
            $list_product_transaction->base_price = $cart->base_price;
            $list_product_transaction->final_price = $cart->final_price;
            $list_product_transaction->count = $cart->count;
            $list_product_transaction->isSell = $cart->isSell;
            $list_product_transaction->isSpecialCase = $cart->isSpecialCase;

            $list_product_transaction->volmetric = $product->volmetric;
            $list_product_transaction->handling_fee = $product->handling_fee;

            $list_product_transaction->save();

            $total += $cart->base_price * $cart->count;
            $total_item += $cart->count;
        }

        $transaction->total_price = $total;
        $transaction->update();

        if (! $member) {
            abort(404);
        }

        return view('pembelian_detail.index', compact('id_pembelian', 'id_customer', 'produk', 'member', 'carts', 'total', 'transaction'));
    
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
            $row['count']      =  $item->count;         
            $row['final_price']    = 'Rp. '. format_uang($item->final_price);
            $row['diskon_percent']      = '<input type="number" class="form-control input-sm discount_percent" data-id="'. $item->id .'" value="'. $item->discount_percent .'">';
            $row['diskon_amount']      = '<input type="number" class="form-control input-sm discount_amount" data-id="'. $item->id .'" value="'. $item->discount_amount .'">';        
            $row['subtotal']    = 'Rp. '. format_uang($item->final_price);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('pembelian_detail.destroy', $item->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
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
            'final_price'    => '',
            'diskon_percent'    => '',
            'diskon_amount'    => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'sku', 'count', 'diskon_percent', 'diskon_amount'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PembelianDetail();
        $detail->id_pembelian = $request->id_pembelian;
        $detail->id_produk = $produk->idt_produk;
        $detail->harga_beli = $produk->harga_beli;
        $detail->jumlah = 1;
        $detail->subtotal = $produk->harga_beli;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
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
        if ($diskon != 0) {
            if ( 0 < $diskon && $diskon <= 100 ) {
                $detail->discount_percent = $diskon;
                $detail->discount_amount = 0;
                $x = $diskon / 100 * $detail->final_price;
                $detail->final_price = $detail->final_price - $x;
            } else {
                $detail->discount_amount = $diskon;
                $detail->discount_percent = 0;
                $detail->final_price = $detail->final_price - $diskon;
            }
        } else {
            $detail->discount_amount = 0;
            $detail->discount_percent = 0;
        }
        $detail->update();

        foreach ($carts as $cart) {
            $total += $cart->final_price;
            $total_item += $cart->count;
        }
        $transaction->total_price = $total;
        $transaction->update();

    }

    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon, $total)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $data  = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
        ];

        return response()->json($data);
    }
}
