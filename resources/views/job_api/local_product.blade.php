@extends('layouts.master')

@section('title')
    Daftar Produk Local
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk Local</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                
                <button style="float: left" onclick="view_local()" class="btn btn-warning  btn-round"><i class="fa fa-cubes"></i> Show Local</button>
                <button style="float: left; margin-left: 5px" onclick="view_online()" class="btn btn-warning  btn-round"><i class="fa fa-cubes"></i> Show isitaman</button>
                <button style="float: right; margin-left: 5px" onclick="sync_stock()" class="btn btn-success  btn-round"><i class="fa fa-handshake-o"></i> Sync Stock All</button>
                <button style="float: right; margin-left: 5px" onclick="replace()" class="btn btn-info  btn-round"><i class="fa fa-files-o"></i> Replace Stock All</button>
                <button style="float: right" onclick="cek()" class="btn btn-warning  btn-round"><i class="fa fa-database"></i> Check All</button>
            </div>
            <div class="box-body table-responsive" id="local">
                <form action="" method="post" class="form-member">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th>SKU Produk</th>
                            <th>Title</th>
                            <th>Local</th>
                            <th>IsiTaman</th>
                            <th>Upload</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                

            </div>
            <div class="box-body table-responsive" id="online">
                <form action="" method="post" class="form-member">
                    @csrf
                    <table class="table-it table-stiped table-bordered" id="table-it">
                        <thead>
                            <th>SKU Produk</th>
                            <th>Title</th>
                            <th>Local</th>
                            <th>IsiTaman</th>
                            <th>Upload</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('job_api.local_data') }}',
            },
            columns: [
                {data: 'sku'},
                {data: 'title'},
                {data: 'stock'},
                {data: 'stock_isitaman'},
                {data: 'isLocal'},
                {data: 'aksi'},
            ]
        });
    });

    $(function () {
        table2 = $('.table-it').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('job_api.isitaman_data') }}',
            },
            columns: [
                {data: 'sku_it'},
                {data: 'title_it'},
                {data: 'stock_it'},
                {data: 'stock_isitaman_it'},
                {data: 'isLocal_it'},
                {data: 'aksi_it'},
            ]
        });
    });

    $(document).ready(function () {
        $('#online').hide();
    });

    function view_local() {
        $('#online').hide();
        $('#local').show();
    }
    
    function view_online() {
        $('#local').hide();
        $('#online').show();
    }

    function stock(url) {
        console.log(url);
        
        $.get(url)
            .done((response) => {
                if (response.status == "sukses_cek") {
                    alert('sukses cek data isiTaman');
                    table.ajax.reload();
                    table2.ajax.reload();
                } else if (response.status == "sukses_update") {
                    alert('sukses update stok dari isiTaman');
                    table.ajax.reload();
                    table2.ajax.reload();
                } else if (response.status == "fail_produk") {
                    alert('gagal cek data ke isiTaman, Produk tidak ditemukan');
                    table.ajax.reload();
                    table2.ajax.reload();
                } else if (response.status == "not_sync") {
                    alert('Data produk belum sinkron, cek halaman sinkronisasi terlebih dahulu');
                    table.ajax.reload();
                    table2.ajax.reload();
                } else if (response.status == "fail_connect") {
                    alert('Gagal terhubung dengan isi taman');
                    table.ajax.reload();
                    table2.ajax.reload();
                }
                else {
                    table.ajax.reload();
                    table2.ajax.reload();
                }
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function cek() {
        $.get(`{{ url('/job_api/check_all') }}`, {
                    // '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    alert('sukses');
                    table.ajax.reload();
                    table2.ajax.reload();                    
                })
                .fail(errors => {
                    alert('fail');
                });
    }

    function sync_stock() {
        $.get(`{{ url('/job_api/sync_stock') }}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    table.ajax.reload(); 
                    table2.ajax.reload();                   
                })
                .fail(errors => {
                });
    }

    function replace() {
        $.get(`{{ url('/job_api/replace') }}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    table.ajax.reload(); 
                    table2.ajax.reload();                   
                })
                .fail(errors => {
                });
    }

    function sync(url) {
        console.log(url);
        $.get(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    if (response.status == "not_upload") {    
                        alert('Data Belum diupload di Isiaman');
                        table.ajax.reload();     
                        table2.ajax.reload();               
                    } else if (response.status == "not_sync") {
                        alert('Data belum sinkron, ada di tabel job');
                        table.ajax.reload();  
                        table2.ajax.reload();                  
                    } else if (response.status == "sukses") {
                        alert('Sukses sinkron stok');
                        table.ajax.reload();  
                        table2.ajax.reload();                  
                    } else {
                        alert('Terjadi kesalahan server');
                        table.ajax.reload();
                        table2.ajax.reload();                    
                    }
                })
                .fail(errors => {
                });    }
    // function addForm(url) {
    //     $('#modal-form').modal('show');
    //     $('#modal-form .modal-title').text('Tambah Diskon');

    //     $('#modal-form form')[0].reset();
    //     $('#modal-form form').attr('action', url);
    //     $('#modal-form [name=_method]').val('post');
    //     $('#modal-form [name=name]').focus();
    // }

    // function editForm(url) {
    //     $('#modal-form').modal('show');
    //     $('#modal-form .modal-title').text('Edit Diskon');

    //     $('#modal-form form')[0].reset();
    //     $('#modal-form form').attr('action', url);
    //     $('#modal-form [name=_method]').val('put');
    //     $('#modal-form [name= name]').focus();
        
    //     $.get(url)
    //         .done((response) => {
    //             $('#modal-form [name=name]').val(response.name);
    //             $('#modal-form [name=discount]').val(response.discount);
    //             $('#modal-form [name=discount_limit]').val(response.discount_limit);
    //             $('#modal-form [name=countlimit]').val(response.count_limit);
    //             alert(' Jancok');
    //             return;
    //         })
    //         .fail((errors) => {
    //             alert('Tidak dapat menampilkan data');
    //             return;
    //         });
    // }

    // function deleteData(url) {
    //     if (confirm('Yakin ingin menghapus data terpilih?')) {
    //         $.post(url, {
    //                 '_token': $('[name=csrf-token]').attr('content'),
    //                 '_method': 'delete'
    //             })
    //             .done((response) => {
    //                 table.ajax.reload();
    //             })
    //             .fail((errors) => {
    //                 alert('Tidak dapat menghapus data');
    //                 return;
    //             });
    //     }
    // }

    // function cetakMember(url) {
    //     if ($('input:checked').length < 1) {
    //         alert('Pilih data yang akan dicetak');
    //         return;
    //     } else {
    //         $('.form-member')
    //             .attr('target', '_blank')
    //             .attr('action', url)
    //             .submit();
    //     }
    // }
</script>
@endpush