<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pendapatan</title>

    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
</head>
<body>
    <h3 class="text-center">Laporan Pendapatan</h3>
    <h4 class="text-center">
        Tanggal {{ tanggal_indonesia($tanggal, false) }}
    </h4>

    <table class="table table-striped">
        <thead>
            <tr>
                {{-- <th width="5%">No</th> --}}
                <th>Nomor Transaksi</th>
                <th>Metode Pembayaran</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Total</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                            <tr>
                                {{-- <td width="5%">{{ $item->DT_RowIndex }}</td> --}}
                                <td>{{ $number_ref[]=$item['number_ref']; }}</td>
                                <td>{{ $payment_method[]=$item['payment_method']; }}</td>
                                <td>{{ $product[]=$item['product']; }}</td>
                                <td>{{ $count[]=$item['count']; }}</td>
                                <td>{{ $base_price[]=$item['base_price']; }}</td>
                                <td>{{ $discount[]=$item['discount']; }}</td>
                                <td>{{ $final_price[]=$item['final_price']; }}</td>

                            </tr>
                        @endforeach
        </tbody>
    </table>
</body>
</html>