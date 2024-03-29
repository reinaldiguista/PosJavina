@extends('layouts.master')

@section('title')
    Transaksi Pembelian Melalui Cart
@endsection

@push('css')
<style>
    .switch {
  position: relative;
  display: inline-block;
  width: 30px;
  height: 17px;
  margin-left: 4px;
  margin-top: 4px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 13px;
  width: 13px;
  left: 2px;
  bottom: 2px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(13px);
  -ms-transform: translateX(13px);
  transform: translateX(13px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 17px;
}

.slider.round:before {
  border-radius: 50%;
}
    
    .hide {
  display: none;
}
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .tampil-kembali {
        font-size: 5em;
        text-align: center;
        height: 100px;
        background: #f3ab11;
    }

    .tampil-terbilang_kembali {
        padding: 10px;
        background: #f0f0f0;
    }

    .judul-bayar {
        font-size: 2em;
        text-align: center;
        height: 50px;
        background: #f3ab11;
    }
    
    .judul-kembali {
        font-size: 2em;
        text-align: center;
        height: 50px;
    }
    .table-pembelian tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
    input[type="radio"]{
        margin: 0 5px 0 5px;
        margin-left: 30px;

    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <table>
                    <tr>
                        <td>Nama Customer </td>
                        <td>&nbsp; : {{ $member->name }}</td>
                    </tr>
                    <tr>
                        <td>Telepon </td>
                        <td>&nbsp;  : {{ $member->phone }}</td>
                    </tr>
                </table>
            </div>

            <div class="form-group row">
                <span class="input-group-btn; col-lg-12">
                    <button style="" onclick="tampilProduk()" class="btn btn-info btn-lg btn-block" type="button">Tambah Produk <i class="fa fa-arrow-right"></i></button>
                </span>
                <div class="col-lg-5">
                    <div class="input-group">
                        {{-- <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}"> --}}
                        <input type="hidden" name="id_produk" id="id_produk">
                        <input type="hidden" class="form-control" name="kode_produk" id="kode_produk">
                        <span class="input-group-btn">
                            {{-- <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button">Cari Produk <i class="fa fa-arrow-right"></i></button>
                            <button onclick="tampilProdukMulti()" class="btn btn-info btn-flat" type="button">Multi Produk <i class="fa fa-arrow-right"></i></button> --}}

                        </span>
                    </div>
                </div>
            </div>

            <div class="box-body">

                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-5">
                            <div class="input-group">
                                
                            </div>
                        </div>
                    </div>
                </form>

                <div class="box-body table-responsive">

                    <table class="table table-stiped table-bordered table-pembelian">
                        <thead>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            {{-- <th>Sub total</th> --}}
                            {{-- <th>discount</th> --}}
                            <th>Total</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>

                <div class="row">
                    
                    <div class="col-lg-8">
                        <div class="judul-bayar bg-primary">Total Belanja</div>
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                        
                        <div class="judul-kembali bg-primary">Total Barang</div>
                        <div class="tampil-kembali bg-primary"></div>
                        <div class="tampil-terbilang_kembali"></div>
                    </div>

                    
                    <div class="col-lg-4">
                        <form action="{{ route('cart.transaction_store') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" id="id_pembelian" name="id_pembelian" value="{{ $id_pembelian }}">
                            <input type="hidden" id="nomor_nota" name="nomor_nota" value="{{ $nomor_nota }}">
                            <input type="hidden" name="transaction_status" value=1>
                            <input type="hidden" name="number_ref" value=0>
                            <input type="hidden" id="customer_id" name="customer_id" value="{{ $member->id }}">
                            <input type="hidden" id="name" name="name" value="{{ $member->name }}">
                            <input type="hidden" id="phone" name="phone" value="{{ $member->phone }}">

                            <input type="hidden" id="case" name="case" >
                            <input type="hidden" name="total_price" id="total_price" value="{{ $total }}">

                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">

                            

                            
                            <div class="form-group row">
                                <label for="position-option" class="col-lg-4 control-label">Diskon</label>
                                    <div class="col-lg-6">
                                        <select class="form-control" id="diskon" name="diskon">
                                            @foreach ($diskon as $discount)
                                                <option value="{{ $discount->discount }}">{{ $discount->name }}</option>
                                            @endforeach
                                            </select>
                                    </div>
                                    
                            </div>
                            
                            {{-- <div class="form-group row">
                                <label for="order_price" class="col-lg-2 control-label">Biaya Kirim</label>
                                <div class="col-lg-8">
                                    <input type="text" name="order_price" id="order_price" class="form-control">
                                </div>
                            </div> --}}

                            <div class="form-group row">
                                <label for="payment_method" class="col-lg-4 control-label">Metode Pembayaran</label>
                                
                                <div class="col-lg-6">
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="Tunai">Tunai</option>
                                        <option value="BCA an Asari">BCA an Asari</option>
                                        <option value="BCA an PT">BCA an PT</option>
                                        <option value="BRI an PT">BRI an PT</option>
                                        <option value="Mandiri an PT">Mandiri an PT</option>
                                        <option value="QRIS BRI">QRIS BRI</option>
                                        <option value="QRIS Mandiri">QRIS Mandiri</option>
                                        <option value="EDC BRI">EDC BRI</option>
                                        <option value="EDC Mandiri">EDC Mandiri</option>
                                        <option value="EDC BCA">EDC BCA</option>
                                        <option value="Shopee">Shopee</option>
                                        <option value="COD">COD</option>
                                        <option onclick="addInvoice()" value="Invoice">Invoice</option>
                                    </select>
                                </div>
                            </div>
   
                            <div class="form-group row" id="form_tf" name="form_tf" style="display: none">
                                <div id="tf_title" >
                                    <label for="nominal" class="col-lg-2 control-label">Tanggal Transfer</label>
                                </div>
                                <div id="if_tf_value"  class="col-lg-8">
                                    <input type="date" id="transfer_date" name="transfer_date" class="form-control" placeholder="masukkan tanggal transfer" >
                                </div>
                            </div>

                            <div class="form-group row" id="form_sender" name="form_sender" style="display: none" >
                                <div id="send_title" >
                                    <label for="nominal" class="col-lg-2 control-label">Nama Pengirim</label>
                                </div>
                                <div id="if_send_value"  class="col-lg-8">
                                    <input type="text" id="name_sender" name="name_sender" class="form-control" placeholder="masukkan nama pengirim" >
                                </div>
                            </div>
 

                            <div class="form-group row">
                                <label for="total_price" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="number" class="col-lg-2 control-label"></label>
                                <div class="col-lg-8">
                                    {{-- <input name=number type="text" id="number" class="form-control"> --}}
                                    {{-- <br>
                                    Uang Pas <input type="radio" name="currency"  id="chf_currency" checked="checked" onChange="currencyChanged()" />
                                    Input Nominal <input type="radio" name="currency" id="usd_currency" onChange="currencyChanged()"/>
                                    <br> --}}
                                    {{-- <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck"/>Uang Pas
                                                    
                                    <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck"/>Input Nominal
                                    <br> --}}
                                    {{-- <label onclick="nominal()" class="btn btn-secondary active">
                                        <input type="radio" name="options1" id="option1" autocomplete="off" checked> Input Nominal
                                        <input type="hidden" name="nominal" id="nominal">
                                    </label>
                                    <label onclick="pas()" class="btn btn-secondary active">
                                          <input type="radio" name="options2" id="option2" autocomplete="off" checked> Uang Pas
                                          <input type="hidden" name="pas" id="pas">
                                    </label> --}}
                                </div>
                                
                            </div>

                            {{-- <div class="form-group row">
                                <label for="nominal" class="col-lg-2 control-label">Nominal Bayar</label>
                                <div id="ifYes" style="display:block" class="col-lg-8">
                                    <input type="text" id="yes" name="yes" class="form-control" readonly>
                                </div>
                                <div id="ifNo" style="display:none" class="col-lg-8">
                                    <input type="text" value=0 id="no" name="no" class="form-control" >
                                </div>
                            </div> --}}

                            <div class="form-group row">
                                <label for="order_type" class="col-lg-4 control-label">Tipe Pemesanan</label>
                                <div class="col-lg-6">
                                    <select name="order_type" id="order_type" class="form-control">
                                        <option value=1>Pilih Tipe Pemesanan</option>
                                        <option value=1>Offline</option>
                                        <option value=2>Online</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="form_tujuan" name="form_tujuan" style="display: none">
                                <div id="tujuan_title" >
                                    <label for="label_tujuan" class="col-lg-2 control-label">Tujuan</label>
                                </div>
                                <div id="tujuan_value"  class="col-lg-6">
                                    <input type="text" id="tujuan" name="tujuan" class="form-control" placeholder="Tujuan" >
                                </div>
                                <div class="col-lg-2">
                                    {{-- <button style="margin-left: 5px" onclick="tampilProdukMaster()" class="btn btn-primary" type="button">Master Produk <i class="fa fa-arrow-right"></i></button> --}}

                                    <button  class="btn btn-success" type="button" onclick="tampilAlamat()"><i class="fa fa-address-card"></i> Buku Alamat</button>
                                </div>
                            </div>

                            <div class="form-group row" id="form_alamat" name="form_alamat" style="display: none" >
                                <div id="alamat_title" >
                                    <label for="label_alamat" class="col-lg-2 control-label">Alamat</label>
                                </div>
                                <div id="alamat_value"  class="col-lg-8">
                                    <input type="text" id="alamat" name="alamat" class="form-control" placeholder="Alamat Pengiriman" >
                                </div>
                                <label class="switch" style="margin-top: 10px">
                                    <input onclick="drop('{{ $id_customer }}', this);" id=""   type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-group row" id="form_penerima" name="form_penerima" style="display: none">
                                <label for="penerima" class="col-lg-2 control-label">Penerima</label>
                                <div class="col-lg-8">
                                    <input type="text" name="penerima" id="penerima" class="form-control" placeholder="Nama Penerima">
                                </div>
                            </div>

                            <div class="form-group row" id="form_hp_penerima" name="form_hp_penerima" style="display: none">
                                <label for="No_HP_Penerima" class="col-lg-2 control-label">No HP Penerima</label>
                                <div class="col-lg-8">
                                    <input type="text" name="No_HP_Penerima" id="No_HP_Penerima" class="form-control" placeholder="No HP Penerima">
                                </div>
                            </div>

                            <div class="form-group row" id="form_ongkir" name="form_ongkir" style="display: none">
                                <label for="order_price" class="col-lg-2 control-label">Ongkir Ekspedisi</label>
                                <div class="col-lg-8">
                                    <input type="text" name="order_price" id="order_price" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row" id="form_ongkir_kurir" name="form_ongkir_kurir" style="display: none">
                                <label for="order_price_kurir" class="col-lg-2 control-label">Ongkir Kurir</label>
                                <div class="col-lg-8">
                                    <input type="text" name="order_price_kurir" id="order_price_kurir" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row" id="form_dropship" name="form_dropship" style="display: none">
                                <label for="dropship" class="col-lg-2 control-label">Dropship</label>
                                <div class="col-lg-8">
                                    <input type="text" name="dropship" id="dropship" class="form-control" placeholder="Nama - No. HP">
                                </div>
                            </div>

                            <div class="form-group row" id="form_resi" name="form_resi" style="display: none">
                                <label for="resi" class="col-lg-2 control-label">Nomor Resi</label>
                                <div class="col-lg-8">
                                    <input type="text" name="resi" id="resi" class="form-control" placeholder="No. Resi">
                                </div>
                            </div>

                            <div class="form-group row" id="form_estimasi_berat" name="form_estimasi_berat" style="display: none">
                                <label for="estimasi_berat" class="col-lg-2 control-label">Estimasi Berat</label>
                                <div class="col-lg-8">
                                    <input type="text" name="estimasi_berat" id="estimasi_berat" class="form-control" placeholder="berat dalam Kg">
                                </div>
                            </div>

                            <div class="form-group row" id="form_ekspedisi" name="form_ekspedisi" style="display: none">
                                <label for="ekspedisi" class="col-lg-4 control-label">Ekspedisi Pengiriman</label>
                    
                                <div class="col-lg-6">
                                    <select name="ekspedisi" id="ekspedisi" class="form-control">
                                        <option value="">Pilih Jenis Ekspedisi</option>
                                        <option value="SICEPAT -- SIUNT">SICEPAT -- SIUNT</option>
                                        <option value="SICEPAT -- HALU">SICEPAT -- HALU</option>
                                        <option value="SICEPAT -- REG">SICEPAT -- REG</option>
                                        <option value="SICEPAT KARGO -- GOKIL">SICEPAT KARGO -- GOKIL</option>
                                        <option value="J&T EXPRESS -- EZ">J&T EXPRESS -- EZ</option>
                                        <option value="J&T EXPRESS -- CARGO">J&T EXPRESS -- CARGO</option>
                                        <option value="JNE -- REG">JNE -- REG</option>
                                        <option value="JNE KARGO -- JTR">JNE KARGO -- JTR</option>
                                        <option value="LION PARCEL">LION PARCEL</option>
                                        <option value="KERETA -- HERONA">KERETA -- HERONA</option>
                                        <option value="KERETA -- KIB">KERETA -- KIB</option>
                                        <option value="KERETA -- K8">KERETA -- K8</option>
                                        <option value="J&T ECONOMY -- ECO">J&T ECONOMY -- ECO</option>
                                        <option value="PUSPA SARI">PUSPA SARI</option>
                                        <option value="J&T EXPRESS -- HEMAT">J&T EXPRESS -- HEMAT</option>
                                        <option value="CLEARESTA">CLEARESTA</option>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="catatan" class="col-lg-2 control-label">Catatan</label>
                                <div class="col-lg-8">
                                    <input type="text-area" name="catatan" id="catatan" class="form-control">
                                </div>
                            </div>
                            @if ($employee->level == 2 || $employee->level == 1)
                                <div class="form-group row">
                                    <label for="created_at" class="col-lg-2 control-label">Tanggal Nota</label>
                                    <div class="col-lg-8">
                                        <input type="date" name="created_at" id="created_at" class="form-control">
                                    </div>
                                </div>
                            @else
                                
                            @endif

                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <a onclick="" href="{{ route('cart.index') }}" class="btn btn-warning btn-sm btn-flat pull-left">
                    <i class="fa fa-check-circle">Kembali</i>
                </a>
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('cart.produk')
@includeIf('cart.produk_transaction')
@includeIf('pembelian_detail.alamat')
@includeIf('cart.stock')
@includeIf('pembelian_detail.invoice')
@includeIf('pembelian_detail.produk_multi')

@endsection

@push('scripts')
<script>
    let table, table2, table3;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('cart.transaction_data', $id_pembelian) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'sku'},
                {data: 'title'},
                {data: 'base_price'},
                {data: 'count'},
                {data: 'total_price'},
                // {data: 'diskon'},
                // {data: 'final_price'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })

        .on('draw.dt', function () {
            loadForm($('#diskon').val());
            kembalian($('#no').val());
            $('#payment_method').prop('selectedIndex',0);
            
        });

        // window.onbeforeunload = (event) => {
        //     event.preventDefault();
        //     return "Apakah Anda yakin semua data telah tersimpan";
        // }

        table2 = $('.table-produk').DataTable();

        
        $(document).on('change', '.count', function () {
            let id = $(this).data('id');
            let jumlah = $(this).val();
            console.log(jumlah);
            $.post(`{{ url('/cart/update_transaction') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'POST',
                    'jumlah' : jumlah
                })
                .done(response => {
                    table.ajax.reload();                    
   
                })
                .fail(errors => {
                    alert('Jumlah melebihi stok');
                    table.ajax.reload();                    

                });
        });
        
        table3 = $('.table-produk_multi').DataTable({
            // responsive: true,
            // processing: true,
            // serverSide: true,
            autoWidth: true,
            ajax: {
                url: '{{ route('produk.data',) }}',
            },
            columns: [
                // {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'sku'},
                {data: 'nama_produk'},
                {data: 'harga_terendah'},
                {data: 'stock'},
                {data: 'select_all'},
                // {data: 'tambahan' , visible: false},
                // {data: 'total'},
                // {data: 'aksi', searchable: false, sortable: false},
            ],
            paginate: true
        });
        
        $(document).on('change', '.discount', function () {
            let id = $(this).data('id');
            let transaction = $('#id_pembelian').val(); 
            let customer = $('#customer_id').val(); 
            let discount = parseInt($(this).val());
            console.log(id);
            console.log(transaction);
            console.log(customer);
            console.log(discount);

            $.post(`{{ url('/pembelian_detail/discount') }}/${id}/${transaction}/${customer}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post',
                    'discount': discount
                })
                .done(response => {
                    table.ajax.reload();                    
                })
                .fail(errors => {
                });
        });

        $( "#payment_method" ).change(function(event) {
            let x = $(this).val();
            let Tunai = "Tunai";
            let cod = "COD";
            let Invoice = "Invoice";
            console.log(x);
                if ($(this).val() === Tunai) {
                    document.getElementById('form_tf').style.display = "none";
                    document.getElementById('form_sender').style.display = "none";
                } else if ($(this).val() === cod) {
                    document.getElementById('form_tf').style.display = "none";
                    document.getElementById('form_sender').style.display = "none";
                } else if ($(this).val() === Invoice) {
                    document.getElementById('form_tf').style.display = "none";
                    document.getElementById('form_sender').style.display = "none";
                    // addInvoice();
                } else {
                    console.log(x);
                    document.getElementById('form_tf').style.display = "block";
                    document.getElementById('form_sender').style.display = "block";
                }

        });

        $( "#order_type" ).change(function(event) {
            let x = $(this).val();
          
            console.log(x);
                if ($(this).val() == 1) {
                    document.getElementById('form_alamat').style.display = "none";
                    document.getElementById('form_tujuan').style.display = "none";
                    document.getElementById('form_ongkir').style.display = "none";
                    document.getElementById('form_ongkir_kurir').style.display = "none";
                    document.getElementById('form_ekspedisi').style.display = "none";
                    document.getElementById('form_dropship').style.display = "none";
                    document.getElementById('form_estimasi_berat').style.display = "none";
                    document.getElementById('form_penerima').style.display = "none";
                    document.getElementById('form_hp_penerima').style.display = "none";

                } else if ($(this).val() == 2) {
                    document.getElementById('form_alamat').style.display = "block";
                    document.getElementById('form_tujuan').style.display = "block";
                    document.getElementById('form_ongkir').style.display = "block";
                    document.getElementById('form_ongkir_kurir').style.display = "block";
                    document.getElementById('form_ekspedisi').style.display = "block";
                    // document.getElementById('form_dropship').style.display = "block";
                    document.getElementById('form_estimasi_berat').style.display = "block";
                    document.getElementById('form_penerima').style.display = "block";
                    document.getElementById('form_hp_penerima').style.display = "block";

                } else {
                    console.log(x);
                    document.getElementById('form_alamat').style.display = "none";
                    document.getElementById('form_tujuan').style.display = "none";
                    document.getElementById('form_ongkir').style.display = "none";
                    document.getElementById('form_ongkir_kurir').style.display = "none";
                    document.getElementById('form_ekspedisi').style.display = "none";
                    document.getElementById('form_dropship').style.display = "none";
                    document.getElementById('form_estimasi_berat').style.display = "none";
                    document.getElementById('form_hp_penerima').style.display = "none";
                }
        });
        
        
        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            loadForm($(this).val());
        });

        $(document).on('input', '#no', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            kembalian($(this).val());
        });

        $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });
    });


    function tampilProduk() {
        $('#modal-produk_transaction').modal('show');
    }
    function tampilAlamat() {
        $('#modal-alamat').modal('show');
    }
    function tampilProdukMulti() {
        // showLoading();
        $('#modal-produk_multi').modal('show');
        // hideLoading();
    }
    function cekStok($sku) {

        $('#stock_produk').val('');
        $('#sku_produk').val('');
        $('#modal-stock').modal('show');
        document.getElementById('sukses').style.display = "none";
        document.getElementById('gagal').style.display = "none";

        let sku = $sku
        $.get($sku)
            .done(response => {
                // document.getElementById('sukses').style.display = "block";
                $('#sku_produk').val(response.sku);
                $('#stock_produk').val(response.stock);
                table.ajax.reload();
            })
            .fail(errors => {
                // document.getElementById('gagal').style.display = "block";
                $('#stock').val('');
                $('#sku_produk').val(response.sku);
                alert('Terjadi Kesalahan di server, cek sinkronasi produk terlebih dahulu ');
                // $('#modal-stock').modal('hide');
                table.ajax.reload();                    
                return;
            })
        // $.get($sku)
        //     .done(response => {
        //         if (response.status == 'fail_job') {
        //             document.getElementById('gagal').style.display = "block";
        //             $('#sku_produk').val(response.sku);
        //             $('#stock_produk').val(response.stock);
        //             alert('Stok produk belum sinkron dengan isiTaman, yakin ingin tetap menambah produk ?');
        //             table.ajax.reload();
        //         } else if(response.status == 'fail_connect'){
        //             document.getElementById('gagal').style.display = "block";
        //             $('#sku_produk').val(response.sku);
        //             $('#stock_produk').val(response.stock);
        //             alert('Gagal terhubung dengan isiTaman, yakin ingin tetap menambah produk ?');
        //             table.ajax.reload();
        //         } else if(response.status == 'fail_produk'){
        //             document.getElementById('gagal').style.display = "block";
        //             $('#sku_produk').val(response.sku);
        //             $('#stock_produk').val(response.stock);
        //             alert('Gagal terhubung dengan isiTaman, produk tak ditemukan. Yakin ingin menambahkan Produk ?');
        //             table.ajax.reload();
        //         } else if (response.status == 'local_product') {
        //             // alert('data local');
        //             document.getElementById('gagal').style.display = "block";
        //             $('#sku_produk').val(response.sku);
        //             $('#stock_produk').val(response.stock);
        //             table.ajax.reload();
        //         } else {
        //             // alert('sukses');
        //             document.getElementById('sukses').style.display = "block";
        //             $('#sku_produk').val(response.sku);
        //             $('#stock_produk').val(response.stock);
        //             table.ajax.reload();
        //         }   
        //     })
        //     .fail(errors => {
        //         document.getElementById('gagal').style.display = "block";
        //         $('#stock').val('');
        //         $('#sku_produk').val(response.sku);
        //         alert('Terjadi Kesalahan di server, cek sinkronasi produk terlebih dahulu ');
        //         // $('#modal-stock').modal('hide');
        //         table.ajax.reload();                    
        //         return;
        //     })
    }

    function kirimProduk() {
        
        let count = $('#produk_kembali').val();  
        let sku = $('#sku_produk').val();
        // $('#stock_produk').val('');
        // $('#sku_produk').val('');
        // $('#modal-stock').modal('show');
        // console.log($sku);
        // let sku = $sku
        $.get(`{{ url('/produk/send_stock') }}/${sku}/${count}`)
            .done(response => {
                if (response.status == "fail") {
                    alert("data tidak ditemukan di isi taman, yakin ingin melanjutkan transaksi ?");
                    $('#produk_kembali').val('');  
                    $('#modal-stock').modal('hide');
                    $('#modal-stock').modal('hide');
                    table.ajax.reload();                    
                } else if (response.status == "fail_produk") {
                    alert("data produk tidak ditemukan di isi taman, data local ditampilkan");
                    $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                    table.ajax.reload();
                } else if (response.status == "fail_job") {
                    alert("data produk belum sinkron dengan isiTaman, data local berhasil ditambah");
                    $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                    table.ajax.reload();
                } else if (response.status == "local_product"){
                    // alert("product local");
                $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                table.ajax.reload();    
                } else {
                alert("sukses");
                $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                table.ajax.reload();  
                }                            
            })
            .fail(errors => {
                alert('Terjadi Kesalahan di server atau data produk tidak ditemukan di isiTaman');
                $('#modal-stock').modal('hide');
                table.ajax.reload();                    

                return;
            })

    }
    
    function KurangiProduk() {
        let count = $('#produk_kembali').val();  
        let sku = $('#sku_produk').val();
        console.log(count);
        // $('#stock_produk').val('');
        // $('#sku_produk').val('');
        // $('#modal-stock').modal('show');
        // console.log($sku);
        // let sku = $sku
        $.get(`{{ url('/produk/remove_stock') }}/${sku}/${count}`)
            .done(response => {
                if (response.status == 'fail_stok') {
                    alert('Jumlah Melebihi Stok');
                } else if (response.status == 'local_product') {
                    tambahProduk();
                    // alert('Berhasil menambah dari data local');
                    $('#produk_kembali').val('');  
                    $('#modal-stock').modal('hide');
                    hideProduk();
                    table.ajax.reload();
                } else if (response.status == 'fail_connect') {
                    tambahProduk();
                    alert('Gagal terhubung dengan isiTaman, cart berhasil ditambah');
                    $('#produk_kembali').val('');  
                    $('#modal-stock').modal('hide');
                    hideProduk();
                    table.ajax.reload();
                } else if (response.status == 'fail_job') {
                    tambahProduk();
                    alert('gagal mengupdate stok isitaman, data berhasil ditambahkan');
                    $('#produk_kembali').val('');  
                    $('#modal-stock').modal('hide');
                    hideProduk();
                    table.ajax.reload(); 
                } else {
                    tambahProduk();
                    // alert('sukses');
                    $('#produk_kembali').val('');  
                    $('#modal-stock').modal('hide');
                    hideProduk();
                    table.ajax.reload();       
                }
            })
            .fail(errors => {
                // tambahProduk();
                alert('Terjadi Kesalahan di server');
                $('#modal-stock').modal('hide');
                hideProduk();
                table.ajax.reload();                    
                return;
            })

    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }
    function hideStock() {
        $('#modal-stock').modal('hide');
    }

    function pilihProduk(id) {
        // $('#kode_produk').val(sku);
        let nomor_nota = $('#nomor_nota').val();
        let id_produk = $('#sku_produk').val();
        let jumlah_produk = $('#produk_kembali').val();
        let harga_satuan = $('#harga_satuan').val();
        let customer_id = $('#customer_id').val();

        console.log(nomor_nota);
        console.log(id_produk);
        console.log(jumlah_produk);
        console.log(harga_satuan);
        console.log(customer_id);
        // KurangiProduk();
        // tambahProduk(id);
        $.post(`{{ url('/pembelian_detail/addProductKasir') }}/${id_produk}/${jumlah_produk}/${harga_satuan}/${customer_id}/${nomor_nota}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post',
                })
                .done(response => {
                    table.ajax.reload();  
                    $('#sku_produk').val('');  
                    $('#produk_kembali').val('');  
                    $('#harga_satuan').val('');  
                    $('#customer_id').val('');  
                    $('#nomor_nota').val('');  
                    $('#modal-stock').modal('hide');

                    // hideProduk();
                    // if (response.status == 'fail_stok') {
                    //     alert('Stok melebihi');
                    //     table.ajax.reload();                    

                    // } else {
                    //     table.ajax.reload();                                            
                    // }
                })
                .fail(errors => {
                    hideStock();
                });
    }

    function tambahProduk(id) {
        let count = 1;  
        let id_produk = id;  

        let sku = $('#sku_produk').val();
        let customer_id = $('#customer_id').val();
        console.log('jumlah',count);
        console.log('id produk',id_produk);
        console.log('customer', customer_id);

            $.post(`{{ url('/pembelian_detail/addProduct') }}/${id_produk}/${count}/${customer_id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post',
                })
                .done(response => {
                    table.ajax.reload();                    
                    // hideProduk();
                    // if (response.status == 'fail_stok') {
                    //     alert('Stok melebihi');
                    //     table.ajax.reload();                    

                    // } else {
                    //     table.ajax.reload();                                            
                    // }
                })
                .fail(errors => {
                    hideProduk();
                });
    }

    function deleteData(url) {
        // if (confirm('Yakin ingin menghapus terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    if (response.status == 'local_product') {
                        // alert('Produk local berhasil dihapus');
                        table.ajax.reload();
                    } else if (response.status == 'masih_di_job') {
                        alert('Data masih di Job, berhasil dihapus dari lokal');
                        table.ajax.reload();
                    } else if (response.status == 'fail_send') {
                        alert('Data terhapus, namun gagal dikirim ke isiTaman');
                        table.ajax.reload();
                    } else {
                        alert('data berhasil dihapus')
                        table.ajax.reload();
                    }                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        // }
    }

    

    function loadForm(diskon = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());
        let x = $('#total_item').val();

        console.log($('.total').text());
        $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon}/${$('.total').text()}`)
            .done(response => {
                if (response.status == "limit") {
                    alert('diskon sudah limit,diskon kembali ke 0');
                    // console.log(response.totalrp);
                    $('#totalrp').val('Rp. '+ response.totalrp);
                    $('#yes').val('Rp. '+ response.totalrp);
                    $('#bayarrp').val('Rp. '+ response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('#total_price').val(response.bayar);
                    $('.tampil-bayar').text('Rp. '+ response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);
                    $('.tampil-kembali').text(x);

                } else {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#yes').val('Rp. '+ response.totalrp);
                $('#bayarrp').val('Rp. '+ response.bayarrp);
                $('#bayar').val(response.bayar);
                $('#total_price').val(response.bayar);
                $('.tampil-bayar').text('Rp. '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);
                $('.tampil-kembali').text(x);

                }
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }
    
    function kembalian(kembalian = 0) {
        console.log(kembalian);
        $('#total').val($('.total').text());
        
        $.get(`{{ url('/pembelian_detail/kembalian') }}/${kembalian}/${$('#bayar').val()}`)
            .done(response => {
                // $('.tampil-kembali').text('Rp. '+ response.sisa);
                // $('.tampil-terbilang_kembali').text(response.terbilang);
            })
            .fail(errors => {
                return;
            })
    }

    function yesnoCheck() {
        if (document.getElementById('yesCheck').checked) {
            document.getElementById('ifYes').style.display = 'block';
            document.getElementById('ifNo').style.display = 'none';
            kembalian(0);
            $('#no').val(0);
        } else {
            document.getElementById('ifYes').style.display = 'none';
            document.getElementById('ifNo').style.display = 'block';
        }
    }

    function tes(id, cb) {
        // console.log($('.add').val(cb.checked));
        console.log(id);
        // var x=  $("#add").is(":checked");
        var x = $(cb).is(":checked");
        console.log(x);

        $.post(`{{ url('/pembelian_detail/case') }}/${id}/${x}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post'
                })
                .done(response => {
                    table.ajax.reload();
                })
                .fail(errors => {
                    alert('gagal');
                });
    }

        function bug() {
            $.get(`{{ url('/pembelian/bug') }}`, {
                    // '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    if (response.status == 'sukses') {
                        alert('success');

                    } else {
                        alert('terjadi kegagalan');
                    }
                    table.ajax.reload();                    
                })
                .fail(errors => {
                });
            }

        function addInvoice() {
            let customer_id = $('#customer_id').val();
            let id_pembelian = $('#id_pembelian').val();
            let total = $('#total').val();
            
           
            console.log(total);
            $.get(`{{ url('/invoice/create') }}/${customer_id}/${id_pembelian}/${total}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    $('#modal-invoice [name=invoice_id]').val(response.invoice_id);
                    $('#modal-invoice [name=customer]').val(response.customer);
                    $('#modal-invoice [name=number_invoice]').val(response.number_invoice);
                    $('#modal-invoice [name=number_ref]').val(response.number_ref);
                    $('#modal-invoice [name=invoice_amount]').val(response.invoice_amount);
                    $('#modal-invoice [name=invoice_debt]').val(response.invoice_debt);
                    $('#modal-invoice [name=created_at]').val(response.created_at);
                    // alert(' Jancok');
                    return;
                })
                .fail(errors => {
                    alert('gagal');
                });

            $('#modal-invoice').modal('show');
            $('#modal-invoice .modal-title').text('Tambah Invoice');

            $('#modal-invoice form')[0].reset();
            $('#modal-invoice form').attr('action', url);
            $('#modal-invoice [name=_method]').val('put');
            $('#modal-invoice [name= name]').focus();
            
            
        }

        function confirm() {
            let invoice_id = $('#invoice_id').val();
            let due_date = $('#due_date').val();
            
            console.log(invoice_id);
            // $('#modal-invoice').modal('hide');

            $.get(`{{ url('/invoice/confirm') }}/${invoice_id}/${due_date}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    $('#modal-invoice').modal('hide');
                    alert(' Sukses');
                    return;
                })
                .fail(errors => {
                    alert('gagal');
                });
        }

        function multi(id, cb) {
        // console.log($('.add').val(cb.checked));
        console.log(id);
        // var x=  $("#add").is(":checked");
        var x = $(cb).is(":checked");
        // var column = table.column($(this).attr('data-column'));
        
        // let true = "true";
        // let false = "false";

        console.log(x);

        if (x === true) {
            tambahProduk(id);
            console.log(x);
            // console.log(x);

        } else {
            console.log(x);
            // console.log(x);

            let customer_id = $('#customer_id').val();
            console.log(customer_id);
            $.post(`{{ url('/pembelian_detail/cancel') }}/${id}/${customer_id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post',
                })
                .done(response => {
                    table.ajax.reload();                    
                })
                .fail(errors => {
                    
                });
            }
        }

        function cust(id, cb) {
        // console.log($('.add').val(cb.checked));
        // console.log(id);
        // var x=  $("#add").is(":checked");
        var x = $(cb).is(":checked");
        // var column = table.column($(this).attr('data-column'));
        // console.log(x);
        // console.log(id);
        // let true = "true";
        // let false = "false";

        // console.log(x);

        if (x === true) {
            console.log(x); 
            // addAddress(id);
            $.get(`{{ url('/pembelian_detail/address') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get',
                })
                .done(response => {
                    console.log(response);
                    $('#alamat').val(response.alamat);
                    $('#tujuan').val(response.tujuan);
                    $('#penerima').val(response.nama);
                    $('#No_HP_Penerima').val(response.phone);
                    $('#modal-alamat').modal('hide');
                })
                .fail(errors => {
                    
                });
            
        } else {
            
            let customer_id = $('#customer_id').val();
            console.log(customer_id);
            $('#alamat').val(null);
            $('#tujuan').val(null);
        }
    }

    function drop(id,cb) {
        let name = $('#name').val();
        let phone = $('#phone').val();
        let dropship = name + " - " + phone;
        var x = $(cb).is(":checked");
        console.log(x);
        if (x === true) {
            document.getElementById('form_dropship').style.display = "block";
            document.getElementById('form_resi').style.display = "block";
            $('#dropship').val(dropship);
        } else {
            document.getElementById('form_dropship').style.display = "none";
            document.getElementById('form_resi').style.display = "none";

            $('#dropship').val(null);
        }
    }

    function dropship(id, cb) {
        console.log(id);
        var x = $(cb).is(":checked");
        console.log(x);

        if (x === true) {
            document.getElementById('form_dropship').style.display = "block";
            document.getElementById('form_resi').style.display = "block";
            $('#dropship').val(response);

            // console.log(x); 
            // // addAddress(id);
            // $.get(`{{ url('/pembelian_detail/dropship') }}/${id}`, {
            //         '_token': $('[name=csrf-token]').attr('content'),
            //         '_method': 'get',
            //     })
            //     .done(response => {
            //         console.log(response);
            //         $('#dropship').val(response);
            //     })
            //     .fail(errors => {
                    
            //     });

            
        } else {
            document.getElementById('form_dropship').style.display = "none";
            document.getElementById('form_resi').style.display = "none";

            $('#dropship').val(null);

            // let customer_id = $('#customer_id').val();
            // console.log(customer_id);
            // $('#tujuan').val(null);
        }
    }

</script>
@endpush