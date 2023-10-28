<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePay;
use App\Models\Member;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Carbon\Carbon;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = Member::all();

        return view('invoice.index', compact('member'));

    }

    public function data()
    {
        // $invoice = Invoice::get();
        $invoice = Invoice::get();

        // var_dump($invoice);
        // return response($invoice);
        return datatables()
        ->of($invoice)
        ->addIndexColumn()
        ->addColumn('no_invoice', function ($invoice) {
                return '<span class="label label-success">'. $invoice->number_invoice .'</span>';
            })
        ->addColumn('customer', function ($invoice) {
                return $invoice->member->name;
            })
        ->addColumn('number_ref', function ($invoice) {
                return '<span class="label label-warning">'. $invoice->number_ref .'</span>';
            })
        ->addColumn('amount', function ($invoice) {
                return format_uang($invoice->invoice_amount);
            }) 
        ->addColumn('debt', function ($invoice) {
                return format_uang($invoice->invoice_debt);
            }) 
        ->addColumn('due_date', function ($invoice) {
                if ($invoice->due_date) {
                    return tanggal_indonesia($invoice->due_date);
                } else {
                }
            })
        ->addColumn('status', function ($invoice) {
                $y = $invoice->status;
                if ($y == 0) {
                    return "Belum Lunas" ;
                } elseif ($y == 1) {
                    return "Lunas" ;
                } else {
                    return "Cancel";
                }
            })
        ->addColumn('created_at', function ($invoice) {
                return tanggal_indonesia($invoice->created_at);
            })
        ->addColumn('aksi', function ($invoice) {
            if ($invoice->status == 1) {
                return '
                    <button type="button" onclick="detail(`'. route('invoice.show_detail', $invoice->id ) .'`)" class="btn btn-xs btn-warning " ><i class="fa fa-eye"></i> Detail</button>
                    <button style="margin-left : 5px" type="button" onclick="cancel(`'. route('invoice.cancel', $invoice->id ) .'`)" class="btn btn-xs btn-danger " ><i class="fa fa-trash"></i> Cancel</button>
                ';
            } elseif ($invoice->status == 2) {
                return '
                    <button type="button" onclick="detail(`'. route('invoice.show_detail', $invoice->id ) .'`)" class="btn btn-xs btn-warning " ><i class="fa fa-eye"></i> Detail</button>
                ';
            } else {
                return '
                    <button type="button" onclick="detail(`'. route('invoice.show_detail', $invoice->id ) .'`)" class="btn btn-xs btn-warning " ><i class="fa fa-eye"></i> Detail</button>
                    <a href="invoice/add_invoice/'. ($invoice->id) .'" style="margin-left: 5px" class="btn btn-xs btn-success "><i class="fa fa-cash"> Bayar</i></a>    
                    <button style="margin-left : 5px" type="button" onclick="cancel(`'. route('invoice.cancel', $invoice->id ) .'`)" class="btn btn-xs btn-danger " ><i class="fa fa-trash"></i> Cancel</button>
                ';
            }
            })
        ->rawColumns(['no_invoice', 'number_ref', 'aksi'])
        ->make(true);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($customer_id, $id_pembelian, $total)
    {
        $transaction = Pembelian::where('id', $id_pembelian)->first();
        $member = Member::where('id', $customer_id)->first();
        
        $invoice = new Invoice();
        $invoice->customer_id = $customer_id;
        // $invoice->number_invoice = 'INV-' . $invoice->id . '-'. $transaction->number_ref;
        $invoice->transaction_id = $id_pembelian;
        $invoice->number_ref = $transaction->number_ref;
        $invoice->invoice_amount = $total;
        $invoice->invoice_debt = $total;
        $invoice->status = 0;
        $invoice->created_at = Carbon::now();
        $invoice->save();

        // $x = tanggal_indonesia($invoice->created_at);

        $data  = [
            'invoice_id' => $invoice->id,
            'customer' => $member->name,
            'number_invoice' => $invoice->number_invoice,
            'number_ref' => $invoice->number_ref,
            'invoice_amount' => 'Rp.' . format_uang($invoice->invoice_amount) ,
            'invoice_debt' => 'Rp. ' . format_uang($invoice->invoice_debt) ,
            'created_at' => $invoice->created_at,
        ];

        return response($data);
    }

    public function confirm($invoice_id,$date)
    {
        $invoice = Invoice::where('id', $invoice_id)->first();
        $transaction = Pembelian::where('id', $invoice->transaction_id)->first();
        
        $invoice->due_date = $date;
        $invoice->update();
        
        $transaction->invoice_status = "1";
        $transaction->update();
        
        // return response("confirm");
        return response($invoice);
    }

    public function hapus($invoice_id)
    {
        $invoice = Invoice::where('id', $invoice_id)->first();
        $invoice->delete();

        return response("delete");
    }
    
    public function cancel($id)
    {
        $invoice = Invoice::find($id);
        $invoice->status = 2;
        $invoice->update();
        
        return response($invoice);

    }
    
    public function bug()
    {
        // $pembelian = Pembelian::where('order_type', 0)->get();
        // $invoice = Invoice::where('number_ref', 0)->get();

        // foreach ($pembelian as $item) {

        //     $item->delete();
        // }

        // foreach ($invoice as $item) {

        //     $item->delete();
        // }

        return response('sukses');
    }
    public function add_invoice($id)
    {
        // return response($id);
        return redirect()->route('invoice_pay.index', $id);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show_detail($id)
    {
        $detail = InvoicePay::where('invoice_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('invoice_id', function ($detail) {
                return '<span class="label label-success">'. $detail->invoice_pay->number_invoice .'</span>';
            })
            ->addColumn('name', function ($detail) {
                return $detail->name;
            })
            ->addColumn('payment_method', function ($detail) {
                return $detail->payment_method;
            })
            ->addColumn('amount', function ($detail) {
                return 'Rp. '. format_uang($detail->amount);
            })
            ->addColumn('payment_date', function ($detail) {
                return tanggal_indonesia($detail->payment_date, false);
            })
            // ->addColumn('aksi', function ($detail) {
            //     return '
            //     <div>
            //         <button onclick="checkout(`'. $detail->customer_id . '`)" class="btn btn-xs btn-info "><i class="fa fa-eye"></i></button>
            //     </div>
            //     '; 
            // })
            ->rawColumns(['invoice_id'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
