{{-- fake receipt
    merchant copy --}}
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
            font-family: "bahnschrift",  light;
        }
        p {
            display: block;
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
                margin: 2px;
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
                width: 58mm;
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
        <h3 style="margin-bottom: 5px;">Taman Arjuno</h3>
        <p>Kreweh, Gunungrejo, Singosari, Malang <br> 0812-3463-5035</p>
    </div>

    <div>
        <p style="float: left"> Tanggal 
        <br>    No. Ref. <br> Nama Kasir <br> Nama Customer </p>
        <p style="float: right; text-align:right" > {{ tanggal_indonesia($transaksi->created_at, false) }}
        <br>  {{ $transaksi->number_ref }} <br> {{ strtoupper(auth()->user()->name) }} <br> {{ ($transaksi->member->name) }} <br></p>
    </div>
    
    
    <div>
        {{-- <p style="text-align: center">---------------------------------</p> --}}
    </div>
    <table id="detail" width="100%" style="border: 0;">
        @foreach ($detail as $item)
            <tr></tr>
            <tr>
                <td colspan="4">{{ $item->produk->nama_produk }}</td>
            </tr>
            <tr>
                
                
                @if ($item->isSpecialCase == 1)
                        @if (0 < $item->discount && $item->discount <= 100 )
                            <td>{{ $item->count }} x  {{ format_uang(  $item->base_price + (($item->base_price * $item->discount / 100) / $item->count )) }}</td>
                            <td></td>
                            <td></td>
                            <td class="text-right"> {{ format_uang($item->final_price) }}</td>
                                
                        @else
                            <td>{{ $item->count }} x  {{ format_uang(  $item->base_price + ($item->discount / $item->count) ) }}</td>
                            <td></td>
                            <td></td>
                            <td class="text-right"> {{ format_uang($item->final_price) }}</td>
                               
                        @endif
                
                @else
                    @if ($item->discount != 0)
                        <td>{{ $item->count }} x  {{ format_uang($item->base_price) }}</td>
                        <td class="text-right"> {{ format_uang($item->count * $item->base_price) }}</td>
                    @else
                        <td>{{ $item->count }} x  {{ format_uang($item->base_price) }}</td>
                        <td></td>  
                        <td></td>  
                        <td class="text-right"> {{ format_uang($item->final_price) }} </td> 
                    @endif
                @endif
                
            </tr>
            <tr>
                
                @if ($item->isSpecialCase == 1)
                    
                @else
                    @if ($item->discount != 0)

                        @if (0 < $item->discount && $item->discount <= 100 )
                            <td>Discount Percent</td>
                            <td colspan="1" class="text-right"> {{ format_uang($item->discount) }} %|</td>
                            <td></td>
                            <td class="text-right"> {{ format_uang($item->final_price) }} </td>  
                        @else
                            <td>Discount Amount</td>
                            <td class="text-right"> {{ format_uang($item->discount) }}|</td>
                            <td></td>
                            <td class="text-right"> {{ format_uang($item->final_price) }} </td>    
                        @endif
                    @else
                    
                    @endif
                
                @endif
                
            </tr>
            <tr>
            </tr>
        @endforeach
        
    </table>
    <p style="text-align: center">---------------------------------</p>

    @if ($transaksi->payment_method == "Invoice")
    <p style="text-align: center">---INVOICE---</p>

    @else

    @endif

    <table width="100%" style="border: 0;">
        <tr>
            <td>Sub Total :</td>
            <td class="text-right">Rp. {{ format_uang($total) }} </td>
        </tr>
        
        <tr>
            @if ($transaksi->discount == 0)
            
            @else
                <td>Grand Diskon :</td>
                @if (0 < $transaksi->discount && $transaksi->discount <= 100 )
                    <td class="text-right">{{ format_uang($transaksi->discount) }} %</td>
                @else
                    <td class="text-right">Rp. {{ format_uang($transaksi->discount) }} </td>
                @endif            

            @endif            
        </tr>
        <tr>
            @if ($transaksi->order_price == 0)
                
            @else    
                <td>Biaya Kirim :</td>
                <td class="text-right">Rp. {{ format_uang($transaksi->order_price) }}</td>
            @endif
        </tr>
        <tr>
            <td>Total Belanja :</td>
            <td class="text-right">Rp. {{ format_uang($transaksi->total_payment) }}</td>
        </tr>
        <tr>
            @if ($transaksi->pay == 0)
                <td>Total Bayar :</td>
                <td class="text-right">Rp. {{ format_uang($transaksi->total_payment) }}</td>
            @else
                <td>Total Bayar :</td>
                <td class="text-right">Rp. {{ format_uang($transaksi->pay) }}</td>
            @endif
        </tr>
        <tr>
            @if ($transaksi->pay == 0)
                
            @else
                
                @if ($transaksi->pay-$transaksi->total_payment == 0)
                    
                @else
                <td>Kembali:</td>
                <td class="text-right">Rp. {{ format_uang($transaksi->pay - $transaksi->total_payment) }}</td>
                @endif
                
            @endif
        </tr>

        
    </table>
    

    
    <table width="100%" style="border: 0;">
        @if ($transaksi->name_sender == "")
            
        @else
        <p style="text-align: center">---------------------------------</p>
        <p style="text-align: center">Pembayaran via Transfer</p>

        <tr>
            <td class="text-left">{{ $transaksi->payment_method }}</td>
            <td  style="text-align: right" >{{ indonesia($transaksi->transfer_date) }}</td>
        </tr>

        <tr>
            <td>Pengirim</td>
            <td style="text-align: right">{{ $transaksi->name_sender }}</td>        
        </tr>
            
        @endif
    
    </table>

    <table width="100%" style="border: 0;">
        @if ($transaksi->catatan == "")        
        @else
        <tr>
            <td>Catatan</td>
        </tr>
        <tr>
            <td style="text-align: left">{{ $transaksi->catatan }}</td>
        </tr>
            @endif

    </table>
    
    <p class="text-center">---------------------------------</p>
    <p class="text-center">Follow Us on Social Media</p>
    <p class="text-center">Youtube : @TamanArjuno <br>
        Facebook : @tamanarjuno <br>
        Instagram : @tamanarjuno <br>
        Twitter : @Tamanarjuno <br>
    </p>
    <p style="margin-bottom: 5px;" class="text-center">TERIMA KASIH</p>
    <p class="text-center">---</p>
    <br>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    
    function deleteRow(r) {
        if 
    }
    
    </script>
</body>
</html>