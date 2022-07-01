<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Kecil</title>

    <?php
    $style = '
    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 10pt;
        }
        table td {
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm 
    ';
    ?>
    <?php 
    $style .= 
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">JAVINA</h3>
        <p>Jalan sesama</p>
    </div>
    <br>
    <div>
        <p style="float: left;">{{ date('d-m-Y') }}</p>
        <p style="float: right">{{ strtoupper(auth()->user()->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    <p>No: {{ tambah_nol_didepan($transaksi->id, 10) }}</p>
    <p>Customer: {{ ($customer->name) }}</p>
    <p class="text-center">===================================</p>
    
    <table width="100%" style="border: 0;">
        @foreach ($detail as $item)
            <tr>
                <td colspan="3">{{ $item->produk->title }}</td>
            </tr>
            <tr>
                <td>{{ $item->count }} x Rp. {{ format_uang($item->base_price) }}     |</td>
                <td class="text-right"> Rp. {{ format_uang($item->count * $item->base_price) }}     |</td>
                <td></td>
            </tr>
            <tr>
                <td>Discount Amount</td>
                <td class="text-right"> Rp. {{ format_uang($item->discount_amount) }}     |</td>
                <td></td>
            </tr>
            <tr>
                <td>Discount Percent</td>
                <td class="text-right"> {{ format_uang($item->discount_percent) }} %     |</td>
                <td></td>
            </tr>
            <tr>
                <td>Final Price</td>
                <td></td>
                <td class="text-right"> Rp. {{ format_uang($item->final_price) }}  </td>
            </tr>
        @endforeach
        
    </table>
    <p class="text-center">--------------------------------------------------------------------------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Sub_Total:</td>
            <td class="text-right">Rp. {{ format_uang($total) }}</td>
        </tr>
        {{-- <tr>
            <td>Total Item:</td>
            <td class="text-right">{{ format_uang($penjualan->count) }}</td>
        </tr> --}}
        <tr>
            <td>Grand Diskon:</td>
            <td class="text-right">{{ format_uang($diskon) }} %</td>
        </tr>
        <tr>
            <td>Total Bayar:</td>
            <td class="text-right">Rp. {{ format_uang($bayar) }}</td>
        </tr>
        {{-- <tr>
            <td>Diterima:</td>
            <td class="text-right">{{ format_uang($penjualan->diterima) }}</td>
        </tr> --}}
        {{-- <tr>
            <td>Kembali:</td>
            <td class="text-right">{{ format_uang($penjualan->diterima - $penjualan->bayar) }}</td>
        </tr> --}}
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>