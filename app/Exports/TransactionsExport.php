<?php

namespace App\Exports;

use App\Models\Pembelian;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Pembelian::all();
        // $type = DB::table('transaction')->select('number_ref','customer_id','total_price'
        // ,'discount','total_payment','payment_method','transafer_date', 'catatan','employee_id'
        // ,'transaction_status','invoice_status','created_at')->get();
        
        $transaction = DB::table('transaction')->get();
        
        $data  = [
            'invoice_id' => $transaction->number_ref,
            'customer' => $transaction->name,
            // 'total_price' => $invoice->number_invoice,
            // 'number_ref' => $invoice->number_ref,
            // 'invoice_amount' => 'Rp.' . format_uang($invoice->invoice_amount) ,
            // 'invoice_debt' => 'Rp. ' . format_uang($invoice->invoice_debt) ,
            // 'created_at' => $invoice->created_at,
        ];
        
        return $data ;
    }
    public function headings(): array
    {
        return [
            'Nomor Transaksi',
            'Nama Customer',
            'Sub Total',
            'Grand Diskon',
            'Total',
            'Metode Pembayaran',
            'Tanggal Transfer',
            'Catatan',
            'Peagawai',
            'Status Transaksi',
            'Status Invoice',
            'Tanggal Dibuat',

        ];
    }
}
