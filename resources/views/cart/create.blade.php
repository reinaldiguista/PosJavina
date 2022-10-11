@extends('layouts.master')

@section('title')
    Tambah Cart
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


.loader {
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.loading {
	z-index: 20;
	position: absolute;
	top: 0;
	left:-5px;
	width: 100%;
	height: 100%;
    background-color: rgba(0,0,0,0.4);
}
.loading-content {
	position: absolute;
	border: 16px solid #f3f3f3; /* Light grey */
	border-top: 16px solid #3498db; /* Blue */
	border-radius: 50%;
	width: 50px;
	height: 50px;
	top: 40%;
	left:35%;
	animation: spin 2s linear infinite;
	}
	
	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
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

    .table-penjualan tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Tambah Cart</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">

            <div class="box-header with-border">
                <table>
                    <tr>
                        <td>Nama Customer</td>
                        <td> : {{ $customer->name }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td> : {{ $customer->phone }}</td>
                    </tr>
                </table>
            </div>

            <div class="box-body">
                    
                <form method="POST" class="form-produk">
                    @csrf
                    
                    <input type="hidden" name="id_customer" id="id_customer" value="{{ $id_customer }}">
                    
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

                </form>

                <div class="box-body table-responsive">
                    Ada Produk Special Case ? <a class="toggle-vis" data-column="0"></a><a class="toggle-vis" data-column="1"></a><a class="toggle-vis" data-column="2"></a><a class="toggle-vis" data-column="3"></a><a class="toggle-vis" data-column="4">Click Here</a><a class="toggle-vis" data-column="5"></a><a class="toggle-vis" data-column="6"></a>
                    <table class="table table-stiped table-bordered table-cart">
                        <thead>
                            <th width="5%">No</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Tambahan</th>
                            <th>Total</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
                
            </div>
            
            <div class="col-lg-4">
                <form action="{{ route('cart.store', $customer->id) }}" class="form-pembelian" method="post">
                    @csrf
                    <input type="hidden" id="customer_id" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" id="sku_tanaman" name="sku_tanaman">

                </form>
            </div>

            <div class="box-footer">
                <a href="{{ route('cart.index') }}" class="btn btn-warning btn-sm btn-flat pull-left">
                    <i class="fa fa-floppy-o"> Simpan Cart</i>
                </a>
                <button type="submit" class="btn btn-success btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-shopping-basket"></i> Kirim Cart Ke Kasir</button>
            </div>
        </div>
    </div>
</div>

@includeIf('cart.produk')
@includeIf('cart.stock')
@endsection

@push('scripts')
<script>

    let table;
    let table2;
    let customer_id ;

    $(function () {
        table = $('.table-cart').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: true,
            ajax: {
                url: '{{ route('cart.new',) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'produk'},
                {data: 'jumlah'},
                {data: 'harga'},
                {data: 'tambahan' , visible: false},
                {data: 'total'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            paginate: false
        });

        
        
    $('.table-produk').DataTable();

    });

    
     $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });

    
    $(document).ready(function () {
 
    $('a.toggle-vis').on('click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column($(this).attr('data-column'));
 
        console.log(column);
        // Toggle the visibility
        column.visible(!column.visible());
        });
    });
    
    $(document).on('change', '.discount', function () {
            let id = $(this).data('id');

            let discount = parseInt($(this).val());

            $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'discount': discount
                })
                .done(response => {
                    table.ajax.reload();                    
                })
                .fail(errors => {
                });
        });
    
    $(document).on('change', '.count', function () {
            let id = $(this).data('id');
            let jumlah = $(this).val();
            $.post(`{{ url('/cart/update') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'POST',
                    'jumlah' : jumlah
                })
                .done(response => {
                    if (response.status == 'local_product_remove') {
                        alert('berhasil menambah produk local');
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
                        alert('berhasil mengurangi produk local');
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
                        alert('data lokal cart berhasil terhapus');
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

    
    


    function tampilProduk() {
        showLoading();
        $('#modal-produk').modal('show');
        hideLoading();
    }

    function pilihProduk() {
        // $('#kode_produk').val(sku);
        KurangiProduk();
        // tambahProduk();
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
                showLoading();
                if (response.status == 'fail_job') {
                    hideLoading();
                    document.getElementById('gagal').style.display = "block";
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    alert('Stok produk belum sinkron dengan isiTaman, yakin ingin tetap menambah produk ?');
                    table.ajax.reload();
                } else if(response.status == 'fail_connect'){
                    hideLoading();
                    document.getElementById('gagal').style.display = "block";
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    alert('Gagal terhubung dengan isiTaman, yakin ingin tetap menambah produk ?');
                    table.ajax.reload();
                } else if(response.status == 'fail_produk'){
                    hideLoading();
                    document.getElementById('gagal').style.display = "block";
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    alert('Gagal terhubung dengan isiTaman, produk tak ditemukan. Yakin ingin menambahkan Produk ?');
                    table.ajax.reload();
                } else if (response.status == 'local_product') {
                    hideLoading();
                    alert('data local');
                    document.getElementById('sukses').style.display = "block";
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    table.ajax.reload();
                } else {
                    hideLoading();
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
                hideLoading();
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
                    alert('Berhasil menambah dari data local');
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

    

    function tambahProduk() {
        let count = $('#produk_kembali').val();  
        let sku = $('#sku_produk').val();
        let customer_id = $('#customer_id').val();
        console.log(count);
        console.log(sku);
        console.log(customer_id);

            $.post(`{{ url('/cart/addProduct') }}/${sku}/${count}/${customer_id}`, {
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
    }

   
    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            console.log(url);
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    if (response.status == 'local_product') {
                        alert('Produk local berhasil dihapus');
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
                    }
                })
                .fail((errors) => {
                    
                alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function showLoading() {
  document.querySelector('#loading').classList.add('loading');
  document.querySelector('#loading-content').classList.add('loading-content');
}

function hideLoading() {
  document.querySelector('#loading').classList.remove('loading');
  document.querySelector('#loading-content').classList.remove('loading-content');
}

function tes(id, cb) {
        // console.log($('.add').val(cb.checked));
        console.log(id);
        // var x=  $("#add").is(":checked");
        var x = $(cb).is(":checked");
        console.log(x);
        // var column = table.column($(this).attr('data-column'));

        var column = table.column('6');
        console.log(column);
        
        // Toggle the visibility
        column.visible(true);

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



    
</script>
@endpush