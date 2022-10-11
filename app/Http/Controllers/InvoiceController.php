<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
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
        $invoice = Invoice::all();

        // var_dump($invoice);
        // return response($invoice);
        return datatables()
        ->of($invoice)
        ->addIndexColumn()
        ->addColumn('no_invoice', function ($invoice) {
                return '<span class="label label-success">'. $invoice->number_invoice .'</span>';
            })
        ->addColumn('customer', function ($invoice) {
                return $invoice->customer_id;
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
                return tanggal_indonesia($invoice->due_date);
            })
            ->addColumn('status', function ($invoice) {
                $y = $invoice->status;
                if ($y == 1) {
                    return "Lunas" ;
                } elseif ($y > 1) {
                    return "Belum Lunas" ;
                } else {
                }
            })
            ->addColumn('created', function ($invoice) {
                return tanggal_indonesia($invoice->created_at);
            })
            ->addColumn('aksi', function ($invoice) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="detail(`'. route('invoice.detail', $invoice->no_invoice ) .'`)" class="btn btn-xs btn-warning " ><i class="fa fa-eye"></i> Detail</button>
                    <button type="button" onclick="cancel(`'. route('invoice.cancel', $invoice->no_invoice ) .'`)" class="btn btn-xs btn-danger " ><i class="fa fa-trash"></i> Cancel</button>
                </div>
                ';
            })
            ->rawColumns(['select_all','no_invoice', 'number_ref', 'aksi'])
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
        $invoice->number_invoice = 'INV-' . $invoice->id . '-'. $transaction->number_ref;
        $invoice->transaction_id = $id_pembelian;
        $invoice->number_ref = $transaction->number_ref;
        $invoice->invoice_amount = $total;
        $invoice->invoice_debt = $total;
        $invoice->status = 0;
        $invoice->created_at = Carbon::now();
        $invoice->save();

        $data  = [
            'customer' => $member->name,
            'number_invoice' => $invoice->number_invoice,
            'number_ref' => $invoice->number_ref,
            'invoice_amount' => $invoice->invoice_amount,
            'invoice_debt' => $invoice->invoice_debt,
            'created_at' => $invoice->created_at
        ];

        return response($data);
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
    public function show(Invoice $invoice)
    {
        //
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
