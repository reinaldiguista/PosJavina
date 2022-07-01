<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Cart;
use Illuminate\Http\Request;
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
        return view('cart.index');
    }

    public function data()
    {
        $carts = Cart::with('member')
                ->select('customer_id', 'employee_id')
                ->where('isSend', 1)
                ->where('flag', 0)
                ->distinct()
                ->get();
        return datatables()
            ->of($carts)
            ->addIndexColumn()
            ->addColumn('select_all', function ($carts) {
                return '
                    <input type="checkbox" name="id[]" value="'. $carts->id .'">
                ';
            })
            ->addColumn('customer_id', function ($carts) {
                return $carts->customer_id ;
            })
            ->addColumn('employee_id', function ($carts) {
                return $carts->employee_id ;
            })
            ->addColumn('aksi', function ($carts) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('cart.show', $carts->customer_id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="checkout(`'. route('cart.checkout', $carts->customer_id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-cart-plus"></i></button>
                    </div>
                ';
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
    public function create()
    {
        //
    }

    public function checkout($id)
    {
        $detail = Cart::with('produk')
            ->where('customer', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_jual']  = 'Rp. '. format_uang($item->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_penjualan_detail .'" value="'. $item->jumlah .'">';
            $row['diskon']      = $item->diskon . '%';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaksi.destroy', $item->id_penjualan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah - (($item->diskon * $item->jumlah) / 100 * $item->harga_jual);;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'harga_jual'  => '',
            'jumlah'      => '',
            'diskon'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->make(true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $member = Member::latest()->first() ?? new Member();

        $member = new Member();
        $member->name = $request->name;
        $member->phone = $request->phone;
        $member->customer_type = $request->customer_type;
        $member->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $detail = Cart::where('customer_id', $id)->get();
        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_id', function ($detail) {
                return $detail->product_id ;
            })
            ->addColumn('base_price', function ($detail) {
                return 'Rp. '. format_uang($detail->base_price);
            })
            ->addColumn('count', function ($detail) {
                return $detail->count;
            })
            ->addColumn('final_price', function ($detail) {
                $x = $detail->base_price;
                $y = $detail->count;
                $z = $x * $y;
                $detail->final_price = $z;
                return 'Rp. '. format_uang($detail->final_price);
            })
            ->addColumn('isSpecialCase', function ($detail) {
                return $detail->isSpecialCase;
            })
            ->addColumn('aksi', function ($detail) {
                return '
                <div>
                    <button onclick="checkout(`'. $detail->customer_id . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                </div>
                '; 
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
        $member = Member::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::find($id);
        $member->delete();

        return response(null, 204);
    }
   
}
