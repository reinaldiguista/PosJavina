@extends('layouts.master')

@section('title')
    Daftar Order Online
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Order Online</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                {{-- <button onclick="addForm()" class="btn btn-info btn-lg btn-block"><i class="fa fa-plus-circle"></i> Proses Transaksi</button> --}}
                <button onclick="addCustomer()" class="btn btn-success btn-lg btn-block"><i class="fa fa-cart-plus"></i> Buat Order</button>
                @empty(! session('id_pembelian'))
                <a href="{{ route('pembelian_detail.index') }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-pencil"></i> Transaksi Aktif</a>
                @endempty
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Nomor Nota</th>
                        <th>Total Harga</th>
                        <th>Payment Method</th>
                        <th>Employee</th>
                        <th>Status Transaksi</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('pembelian.cart')
@includeIf('pembelian.detail')
@includeIf('pembelian.customer_online')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pembelian.data_online') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'customer'},
                {data: 'number_ref'},
                {data: 'total_harga'},
                {data: 'payment_method'},
                {data: 'employee_id'},
                {data: 'transaction_status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

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
                {data: 'total_price'},
            ]
        })
    });

    function addForm() {
        $('#modal-cart').modal('show');
    }

    function addCustomer() {
        $('#modal-customer_online').modal('show');
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function cetakNota(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table1.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    table1.ajax.reload();
                });
        }
    }

    function cetakNota(url) {
        console.log(url);
        popupCenter(url, 625, 500);
    }

    function popupCenter(url, title, w, h) {
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;

        const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left       = (width - w) / 2 / systemZoom + dualScreenLeft
        const top        = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow  = window.open(url, title, 
        `
            scrollbars=yes,
            width  = ${w / systemZoom}, 
            height = ${h / systemZoom}, 
            top    = ${top}, 
            left   = ${left}
        `
        );

        if (window.focus) newWindow.focus();
    }

    function refund(url) {
        if (confirm('Yakin ingin refund transaksi terpilih?')) {
            
            $.get(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get',
                })
                .done(response => {
                    console.log(response.status);
                    if (response.status == 'success') {
                        alert('Transaksi berhasil refund');
                        table.ajax.reload();                    
                    } else {
                        alert('data berhasil terhapus dari local, namun belum sinkron');
                        table.ajax.reload();                    
                    }                 
                })
                .fail(errors => {
                    console.log(respones.status);
                    alert('data berhasil terhapus dari local, namun belum sinkron');
                    table.ajax.reload();                    
                });
        }
    }
</script>
@endpush