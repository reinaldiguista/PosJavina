@extends('layouts.master')

@section('title')
    Daftar Stock
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Stock</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-info btn-lg btn-block"><i class="fa fa-plus-circle"></i> Buat Baru</button>
                {{-- <button onclick="addCustomer()" class="btn btn-success btn-lg btn-block"><i class="fa fa-cart-plus"></i> Buat Transaksi</button> --}}
                @empty(! session('id_pembelian'))
                {{-- <a href="{{ route('pembelian_detail.index') }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-pencil"></i> Transaksi Aktif</a> --}}
                @endempty
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-stock">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Nama</th>
                        <th>Total Barang</th>
                        <th>Asal</th>
                        <th>Lokasi Penerimaan</th>
                        <th>Penerima</th>
                        <th>Pengirim</th>
                        <th>Catatan</th>
                        <th>Status Penerimaan</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('stock.form')
@includeIf('stock.detail')
@includeIf('stock.edit')
{{-- @includeIf('pembelian.customer') --}}
@endsection

@push('scripts')
<script>
    let table, table1, table3;

    $(function () {
        table3 = $('.table-stock').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('stock.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'tipe'},
                {data: 'name'},
                {data: 'total_barang'},
                {data: 'asal'},
                {data: 'lokasi_penerimaan'},
                {data: 'pengirim'},
                {data: 'penerima'},
                {data: 'catatan'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });



        $("#tipe").change(function (event) {
                let x = $(this).val();
                let stok_in = "Stok Masuk"; 
                let stok_out = "Stok Keluar"; 
                // console.log(x);
                // document.getElementById('stok_out').style.display = "none";
                // document.getElementById('stok_in').style.display = "none";

                if ($(this).val() == stok_in) {
                    console.log(stok_in);
                    document.getElementById('stok_out').style.display = "none";
                    document.getElementById('stok_in').style.display = "block";
                } else {
                    console.log(stok_out);
                    document.getElementById('stok_out').style.display = "block";
                    document.getElementById('stok_in').style.display = "none";
                }
        })
        
        $("#name_in").change(function (event) {
                let x = $(this).val();
                let lainnya = "Lainnya"; 
                // console.log(x);
                // document.getElementById('stok_out').style.display = "none";
                // document.getElementById('stok_in').style.display = "none";

                if ($(this).val() == lainnya) {
                    console.log(stok_in);
                    document.getElementById('lainnya').style.display = "block";
                } else {
                    console.log(stok_out);
                    document.getElementById('lainnya').style.display = "none";
                }
        })

        $("#name_out").change(function (event) {
                let x = $(this).val();
                let lainnya = "Lainnya"; 
                // console.log(x);
                // document.getElementById('stok_out').style.display = "none";
                // document.getElementById('stok_in').style.display = "none";

                if ($(this).val() == lainnya) {
                    console.log(stok_in);
                    document.getElementById('lainnya').style.display = "block";
                } else {
                    console.log(stok_out);
                    document.getElementById('lainnya').style.display = "none";
                }
        })


        // $( "#tipe" ).change(function(event) {
        //     let x = $(this).val();
        //     let in = "Stok Masuk";
        //     let out = "Stok Keluar";
        //     console.log(x);
        //         if ($(this).val() === in) {
        //             document.getElementById('stok_out').style.display = "none";
        //             document.getElementById('stok_in').style.display = "block";
        //         } else if ($(this).val() === out) {
        //             document.getElementById('stok_out').style.display = "block";
        //             document.getElementById('stok_in').style.display = "none";
        //         } else {
        //             console.log(x);
        //             document.getElementById('stok_out').style.display = "none";
        //             document.getElementById('stok_in').style.display = "none";
        //         }
        // });
        

        $('.table-cart').DataTable();
        $('.table-customer').DataTable();
        
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'jumlah'},
                {data: 'catatan'},
            ]
        })
    });

    function addForm() {
        $('#modal-form').modal('show');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=title]').focus();
    }

    function tambah(url) {
        $.get(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done((response) => {
                    console.log(url);
         
                })
                .fail((errors) => {
                    
                });
    }

    // function edit(url) {
    //     console.log(url);
    // }

    function edit(url) {
        $('#modal-edit').modal('show');
        $('#modal-edit .modal-title').text('Edit Produk');

        $('#modal-edit form')[0].reset();
        $('#modal-edit form').attr('action', url);
        $('#modal-edit [name=_method]').val('put');
        $('#modal-edit [name=title]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-edit [name=tipe]').val(response.tipe);
                $('#modal-edit [name=tangga]').val(response.tanggal);
                $('#modal-edit [name=stok_in]').val(response.stok_in);
                $('#modal-edit [name=stok_out]').val(response.stok_out);
                $('#modal-edit [name=lainnya]').val(response.lainnya);
                $('#modal-edit [name=pengirim]').val(response.pengirim);
                $('#modal-edit [name=asal]').val(response.asal);
                $('#modal-edit [name=penerima]').val(response.penerima);
                $('#modal-edit [name=lokasi_penerimaan]').val(response.lokasi_penerimaan);
                $('#modal-edit [name=catatan]').val(response.catatan);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }
    
    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }
    function status(url) {
        console.log(url);
    }

    // function addCustomer() {
    //     $('#modal-customer').modal('show');
    // }

    // function showDetail(url) {
    //     $('#modal-detail').modal('show');

    //     table1.ajax.url(url);
    //     table1.ajax.reload();
    // }

    // function cetakNota(url) {
    //     $('#modal-detail').modal('show');

    //     table1.ajax.url(url);
    //     table1.ajax.reload();
    // }

    // function deleteData(url) {
    //     if (confirm('Yakin ingin menghapus data terpilih?')) {
    //         $.post(url, {
    //                 '_token': $('[name=csrf-token]').attr('content'),
    //                 '_method': 'delete'
    //             })
    //             .done((response) => {
    //                 table1.ajax.reload();
    //             })
    //             .fail((errors) => {
    //                 alert('Tidak dapat menghapus data');
    //                 table1.ajax.reload();
    //             });
    //     }
    // }

    // function cetakNota(url) {
    //     console.log(url);
    //     popupCenter(url, 625, 500);
    // }

    // function popupCenter(url, title, w, h) {
    //     const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
    //     const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;

    //     const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    //     const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    //     const systemZoom = width / window.screen.availWidth;
    //     const left       = (width - w) / 2 / systemZoom + dualScreenLeft
    //     const top        = (height - h) / 2 / systemZoom + dualScreenTop
    //     const newWindow  = window.open(url, title, 
    //     `
    //         scrollbars=yes,
    //         width  = ${w / systemZoom}, 
    //         height = ${h / systemZoom}, 
    //         top    = ${top}, 
    //         left   = ${left}
    //     `
    //     );

    //     if (window.focus) newWindow.focus();
    // }

    // function refund(url) {
    //     if (confirm('Yakin ingin refund transaksi terpilih?')) {
            
    //         $.get(url, {
    //                 '_token': $('[name=csrf-token]').attr('content'),
    //                 '_method': 'get',
    //             })
    //             .done(response => {
    //                 console.log(response.status);
    //                 if (response.status == 'success') {
    //                     alert('Transaksi berhasil refund');
    //                     table.ajax.reload();                    
    //                 } else {
    //                     alert('data berhasil terhapus dari local, namun belum sinkron');
    //                     table.ajax.reload();                    
    //                 }                 
    //             })
    //             .fail(errors => {
    //                 console.log(respones.status);
    //                 alert('data berhasil terhapus dari local, namun belum sinkron');
    //                 table.ajax.reload();                    
    //             });
    //     }
    // }
</script>
@endpush