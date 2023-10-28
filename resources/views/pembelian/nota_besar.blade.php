<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota PDF</title>

    <style>
        table td {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
        }
        table.data td,
        table.data th {
            border: 1px solid #ccc;
            padding: 5px;
        }
        table.data {
            border-collapse: collapse;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td rowspan="4" width="60%">
                <h1>FAKTUR PENJUALAN</h1>
            </td>
            <td>Tanggal</td>
            <td>: {{ tanggal_indonesia($transaksi->created_at, false) }}</td>
        </tr>
        <tr>
            <td>Nomor Transaksi</td>
            <td>: {{ $transaksi->number_ref }}</td>
        </tr>
        <tr>
            <td>Nama Customer</td>
            <td>: {{ ($transaksi->member->name) }}</td>
        </tr>
    </table>

    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Nama</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Sub Total</th>
                <th>Diskon</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $key => $item)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $item->produk->sku }}</td>
                    <td>{{ $item->produk->title }}</td>
                    <td class="text-right">{{ format_uang($item->base_price) }}</td>
                    <td class="text-right">{{ format_uang($item->count) }}</td>
                    <td class="text-right">{{ format_uang($item->base_price * $item->count) }}</td>
                    @if (0 < $item->discount && $item->discount <= 100)
                        <td class="text-right">{{ $item->diskon }} %</td>                        
                    @else
                        <td class="text-right">Rp. {{ format_uang($item->diskon) }}</td>
                    @endif
                    <td class="text-right">{{ format_uang($item->final_price) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><b>Sub Total</b></td>
                <td class="text-right"><b>{{ format_uang($total) }}</b></td>
            </tr>
            <tr>
                <td colspan="7" class="text-right"><b>Diskon</b></td>
                @if (0 < $transaksi->discount && $transaksi->discount <= 100 )
                    <td class="text-right"><b>{{ format_uang($transaksi->discount) }} %</b></td>
                @else
                    <td class="text-right"><b>Rp. {{ format_uang($transaksi->discount) }}</b></td>
                @endif

            </tr>
            <tr>
                @if ($transaksi->order_price == 0)
                    
                @else
                    <td colspan="7" class="text-right"><b>Biaya Kirim</b></td>
                    <td class="text-right"><b>Rp. {{ format_uang($transaksi->order_price) }}</b></td>
                @endif
                
            </tr>
            <tr>
                <td colspan="7" class="text-right"><b>Total Belanja</b></td>
                <td class="text-right"><b>{{ format_uang($transaksi->total_payment) }}</b></td>
            </tr>
            <tr>
                @if ($transaksi->pay == 0)
                    <td colspan="7" class="text-right"><b>Total Bayar</b></td>
                    <td class="text-right"><b>{{ format_uang($transaksi->total_payment) }}</b></td>
                @else
                    <td colspan="7" class="text-right"><b>Total Bayar</b></td>
                    <td class="text-right"><b>{{ format_uang($transaksi->pay) }}</b></td>
                @endif 
            </tr>
            <tr>
                @if ($transaksi->pay == 0)
                    
                @else
                    @if ($transaksi->pay-$transaksi->total_payment == 0)
                        
                    @else
                        <td colspan="7" class="text-right"><b>Kembali</b></td>
                        <td class="text-right"><b>{{ format_uang($transaksi->pay - $transaksi->total_payment) }}</b></td>
                    @endif
                
                @endif 
            </tr>
            <tr>
                <td colspan="7" class="text-right"><b>Metode Pembayaran</b></td>
                <td class="text-right"><b>{{ ($transaksi->payment_method) }}</b></td>
            </tr>
        </tfoot>
    </table>

    <table width="100%">
        <tr>
            <td><b>Terimakasih telah berbelanja dan sampai jumpa</b></td>
            <td class="text-center">
                Kasir
                <br>
                <br>
                {{ auth()->user()->name }}
            </td>
        </tr>
    </table>
</body>
</html>