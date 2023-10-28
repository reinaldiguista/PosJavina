<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePay;
use App\Models\Produk;
use App\Models\Stock;
use Illuminate\Http\Request;

class InvoicePayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $invoice = Invoice::find($id);
        $id_invoice = $id;
        $invoice_pay = InvoicePay::where('invoice_id', $invoice->id)->get();
        $produk = Produk::orderBy('title', 'asc')->get();
        return view('invoice_pay.index', compact('produk', 'invoice','invoice_pay', 'id_invoice'));
    }

    public function data($id)
    {
        $invoice = Invoice::where('id', $id)->get();
        $invoice_pay = InvoicePay::where('invoice_id', $id)->where('status', 1)->get();
        return datatables()
            ->of($invoice_pay)
            ->addIndexColumn()
            // ->addColumn('select_all', function ($carts) {
            //     return '
            //         <input type="checkbox" name="id[]" value="'. $carts->id .'">
            //     ';
            // })
            ->addColumn('name', function ($invoice_pay) {
                return $invoice_pay->name;
            })
            ->addColumn('payment_method', function ($invoice_pay) {
                return $invoice_pay->payment_method;
            })
            ->addColumn('amount', function ($invoice_pay) {
                return format_uang($invoice_pay->amount);
            })
            ->addColumn('payment_date', function ($invoice_pay) {
                return tanggal_indonesia($invoice_pay->payment_date);
            })
            ->addColumn('note', function ($invoice_pay) {
                return $invoice_pay->note;
            })
            ->addColumn('aksi', function ($invoice_pay) {
                    return '
                    <div class="btn-group">
                        <button onclick="deleteData(`'. route('invoice_pay.destroy', $invoice_pay->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i> Hapus</button>
                    </div>
                    ';
            })
            ->rawColumns(['aksi'])
            ->make(true);

    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // $invoice_pay = InvoicePay::create($request->all());
        // // $produk->sku = "sku";
        // // $produk->stock = 10;
        // // $produk->update();

        // // $produk = new Produk();
        // // $produk->title = $request->title;
        // // $produk->sku = "sku";
        // // $produk->price = $request->customer_type;
        // // $produk->save();


        // return response($request);
        // var_dump($request);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $invoice_pay = InvoicePay::create($request->all());

        // var_dump($invoice_pay);  

        $invoice = Invoice::find($request->invoice_id);
        $invoice->invoice_debt -= $invoice_pay->amount;
        $invoice->update();


        if ($invoice->invoice_debt == 0) {
            $invoice->status = 1;
            $invoice->update();

            return redirect()->route('invoice.index');

        } else {
            $id_invoice = $request->invoice_id;
            $invoice_pay = InvoicePay::where('invoice_id', $invoice->id)->get();
            $produk = Produk::orderBy('title', 'asc')->get();
            
            return view('invoice_pay.index', compact('produk', 'invoice','invoice_pay', 'id_invoice'));        
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoicePay  $invoicePay
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice_pay = InvoicePay::find($id);
        
        
        return response()->json($invoice_pay);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoicePay  $invoicePay
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoicePay $invoicePay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoicePay  $invoicePay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoicePay $invoicePay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoicePay  $invoicePay
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice_pay = InvoicePay::find($id);
        
        $invoice = Invoice::find($invoice_pay->invoice_id);
        $invoice->invoice_debt += $invoice_pay->amount;
        $invoice->update();
        
        $invoice_pay->delete();

        return response(null, 204);
    }
}
