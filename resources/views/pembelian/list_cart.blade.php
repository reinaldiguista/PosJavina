@extends('layouts.master')

@section('title')
    Daftar Cart
@endsection
@section('breadcrumb')
    @parent
    <li class="active"> Daftar Cart</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addCart()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Cart Baru</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">
                            <input type="checkbox" name="select_all" id="select_all">
                        </th>
                        <th width="5%">No</th>
                        <th>Nama Customer</th>
                        <th>Pegawai</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>     
                        {{-- <div class="action_btn">
                            <a href="{{ route('transaksi.index')}}" class="btn btn-warning">Edit</a>
                          </div>                    --}}
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('cart.detail')
@includeIf('cart.checkout')
@includeIf('pembelian.customer')

@endsection

@push('scripts')
<script>
    let table, table1;
    let customer_id;
    $(function () {
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('cart.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'customer_id'},
                {data: 'employee_id'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('.table-customer').DataTable();


        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'product_id'},
                {data: 'base_price'},
                {data: 'count'},
                {data: 'final_price'},
                {data: 'isSpecialCase'},
                {data: 'aksi'},
            ]
        })
    });

    function showDetail(url) {
        {{ route('cart.data') }}
        $('#modal-detail').modal('show');
        table1.ajax.url(url);
        table1.ajax.reload();
    }


    function addCart() {
        $('#modal-customer').modal('show');
    }


    function checkout(url) {
        $('#modal-checkout').modal('checkout');
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
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush