@extends('layouts.master')

@section('title')
    Detail Stok
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
                        <td>Tipe </td>
                        <td>&nbsp; : {{ $stock->tipe }}</td>
                    </tr>
                    <tr>
                        <td>Keterangan </td>
                        <td>&nbsp;  : {{ $stock->name }}</td>
                    </tr>
                </table>
            </div>

            <div class="form-group row">
                <div class="col-lg-5">
                    <div class="input-group">
                        {{-- <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}"> --}}
                        <input type="hidden" name="id_produk" id="id_produk">
                        <input type="hidden" class="form-control" name="kode_produk" id="kode_produk">
                        <span class="input-group-btn">
                            <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button">Cari Produk <i class="fa fa-arrow-right"></i></button>
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
                            <th>Catatan</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>

                <div class="row">
                    
                    <div class="col-lg-10" style="display: none">
                        <div class="judul-bayar bg-primary">Total Belanja</div>
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                        
                        <div class="judul-kembali bg-primary">Uang Kembalian</div>
                        <div class="tampil-kembali bg-primary"></div>
                        <div class="tampil-terbilang_kembali"></div>
                    </div>

                    
                    <div class="col-lg-4">
                        <form action="{{ route('stock.store_stock') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" id="id_stock" name="id_stock" value="{{ $stock->id }}">
                            <input type="hidden" name="transaction_status" value=1>
                            <input type="hidden" name="number_ref" value=0>
                            <input type="hidden" id="customer_id" name="customer_id" value="{{ $stock->id }}">

                            <input type="hidden" id="case" name="case" >
                            <input type="hidden" name="total_price" id="total_price" value="{{ $stock->id }}">

                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item" >
                            <input type="hidden" name="bayar" id="bayar">

                            {{--  --}}

                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <a onclick="bug()" href="{{ route('stock.index') }}" class="btn btn-warning btn-sm btn-flat pull-left">
                    <i class="fa fa-check-circle">Kembali</i>
                </a>
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Konfirmasi Barang</button>
            </div>
        </div>
    </div>
</div>

@includeIf('stock_detail.produk')
@includeIf('stock_detail.stock')
@includeIf('pembelian_detail.invoice')
@endsection

@push('scripts')
<script>
    let table, table2;
    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('stock_detail.data', $id_stock) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'sku'},
                {data: 'title'},
                {data: 'price'},
                {data: 'count'},
                {data: 'catatan'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })

        .on('draw.dt', function () {
            // loadForm($('#diskon').val());
            // kembalian($('#no').val());
            // $('#payment_method').prop('selectedIndex',0);
            
        });

        table2 = $('.table-produk').DataTable();

        
        $(document).on('change', '.count', function () {
            let id = $(this).data('id');
            let jumlah = $(this).val();
            console.log(jumlah);
            $.post(`{{ url('/cart/update') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'POST',
                    'jumlah' : jumlah
                })
                .done(response => {
                    if (response.status == 'local_product_remove') {
                        // alert('berhasil menambah produk local');
                        table.ajax.reload();
                    } else if(response.status == 'fail_job_remove'){
                        alert('Produk masih belum sinkron, data berhasil ditambah');
                        table.ajax.reload();

                    } else if(response.status == 'sukses_remove'){
                        table.ajax.reload();

                    } else if (response.status == 'fail_connect_remove') {
                        alert('Gagal terhubung dengan isiTaman, data berhasil ditambahkan');
                        table.ajax.reload();

                    } else if(response.status == 'local_product_add'){
                        // alert('berhasil mengurangi produk local');
                        table.ajax.reload();

                    } else if(response.status == 'fail_job_add'){
                        alert('Produk masih belum sinkron, data berhasil berkurang');
                        table.ajax.reload();

                    } else if (response.status == 'sukses_add') {
                        table.ajax.reload();

                    } else if(response.status == 'fail_connect_add'){
                        alert('Gagal terhubung dengan isiTaman, data berhasil berkurang');
                        table.ajax.reload();

                    } else if(response.status == 'local_product_delete'){
                        // alert('data lokal cart berhasil terhapus');
                        table.ajax.reload();

                    } else if (response.status == 'fail_job_delete') {
                        alert('Produk masih belum sinkron, cart berhasil dihapus');
                        table.ajax.reload();

                    } else if(response.status == 'sukses_delete'){
                        table.ajax.reload();

                    } else if (response.status == 'fail_connect_delete') {
                        alert('Gagal terhubung dengan isiTaman, cart berhasil dihapus');
                        table.ajax.reload();

                    } else {
                        alert('jumlah stok tidak memenuhi');
                        table.ajax.reload();
                    }                   
                })
                .fail(errors => {
                    alert('Jumlah melebihi stok');
                    table.ajax.reload();                    

                });
        });

        
        $(document).on('change', '.discount', function () {
            let id = $(this).data('id');
            let transaction = $('#id_stock').val(); 
            let customer = $('#customer_id').val(); 
            let discount = parseInt($(this).val());

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
                } else {
                    console.log(x);
                    document.getElementById('form_tf').style.display = "block";
                    document.getElementById('form_sender').style.display = "block";
                }
        });
        
        
        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            // loadForm($(this).val());
        });

        $(document).on('input', '#no', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            // kembalian($(this).val());
        });

        $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });
    });


    function tampilProduk() {
        $('#modal-produk').modal('show');
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
                if (response.status == 'fail_job') {
                    document.getElementById('gagal').style.display = "block";
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    alert('Stok produk belum sinkron dengan isiTaman, yakin ingin tetap menambah produk ?');
                    table.ajax.reload();
                } else if(response.status == 'fail_connect'){
                    document.getElementById('gagal').style.display = "block";
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    alert('Gagal terhubung dengan isiTaman, yakin ingin tetap menambah produk ?');
                    table.ajax.reload();
                } else if(response.status == 'fail_produk'){
                    document.getElementById('gagal').style.display = "block";
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    alert('Gagal terhubung dengan isiTaman, produk tak ditemukan. Yakin ingin menambahkan Produk ?');
                    table.ajax.reload();
                } else if (response.status == 'local_product') {
                    // alert('data local');
                    document.getElementById('gagal').style.display = "block";
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    table.ajax.reload();
                } else {
                    // alert('sukses');
                    document.getElementById('sukses').style.display = "block";
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    table.ajax.reload();
                }   
            })
            .fail(errors => {
                document.getElementById('gagal').style.display = "block";
                $('#stock').val('');
                $('#sku_produk').val(response.sku);
                alert('Terjadi Kesalahan di server, cek sinkronasi produk terlebih dahulu ');
                // $('#modal-stock').modal('hide');
                table.ajax.reload();                    
                return;
            })
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

    function pilihProduk() {
        // $('#kode_produk').val(sku);
        // KurangiProduk();
        tambahProduk();
    }

    function tambahProduk() {
        let count = $('#produk_kembali').val();  
        let sku = $('#sku_produk').val();
        let id_stock = $('#id_stock').val();
        $('#modal-stock').modal('hide');

        console.log(count);
        console.log(sku);
        console.log(id_stock);

            $.post(`{{ url('/stock_detail/addProduct') }}/${sku}/${count}/${id_stock}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post',
                })
                .done(response => {
                    if (response.status == 'fail_stok') {
                        alert('Stok melebihi');
                        table.ajax.reload();                    

                    } else {
                        table.ajax.reload();                                            
                    }
                })
                .fail(errors => {
                });

        hideProduk();


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
        table.ajax.reload();

    }

    

    // function loadForm(diskon = 0) {
    //     $('#total').val($('.total').text());
    //     $('#total_item').val($('.total_item').text());
    //     console.log($('.total').text());
    //     $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon}/${$('.total').text()}`)
    //         .done(response => {
    //             if (response.status == "limit") {
    //                 alert('diskon sudah limit,diskon kembali ke 0');
    //                 // console.log(response.totalrp);
    //                 $('#totalrp').val('Rp. '+ response.totalrp);
    //                 $('#yes').val('Rp. '+ response.totalrp);
    //                 $('#bayarrp').val('Rp. '+ response.bayarrp);
    //                 $('#bayar').val(response.bayar);
    //                 $('#total_price').val(response.bayar);
    //                 $('.tampil-bayar').text('Rp. '+ response.bayarrp);
    //                 $('.tampil-terbilang').text(response.terbilang);

    //             } else {
    //             $('#totalrp').val('Rp. '+ response.totalrp);
    //             $('#yes').val('Rp. '+ response.totalrp);
    //             $('#bayarrp').val('Rp. '+ response.bayarrp);
    //             $('#bayar').val(response.bayar);
    //             $('#total_price').val(response.bayar);
    //             $('.tampil-bayar').text('Rp. '+ response.bayarrp);
    //             $('.tampil-terbilang').text(response.terbilang);
    //             }
    //         })
    //         .fail(errors => {
    //             alert('Tidak dapat menampilkan data');
    //             return;
    //         })
    // }
    
    // function kembalian(kembalian = 0) {
    //     console.log(kembalian);
    //     $('#total').val($('.total').text());
        
    //     $.get(`{{ url('/pembelian_detail/kembalian') }}/${kembalian}/${$('#bayar').val()}`)
    //         .done(response => {
    //             $('.tampil-kembali').text('Rp. '+ response.sisa);
    //             $('.tampil-terbilang_kembali').text(response.terbilang);
    //         })
    //         .fail(errors => {
    //             return;
    //         })
    // }

    function yesnoCheck() {
        if (document.getElementById('yesCheck').checked) {
            document.getElementById('ifYes').style.display = 'block';
            document.getElementById('ifNo').style.display = 'none';
            // kembalian(0);
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


</script>
@endpush