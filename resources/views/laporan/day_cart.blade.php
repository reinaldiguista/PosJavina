@extends('layouts.master')

@section('title')
    Detail Keranjang {{ $tanggalAkhir }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css"> --}}
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Laporan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="updatePeriode()" class="btn btn-info btn-block btn-flat"><i class="fa fa-plus-circle"></i> Ubah Periode</button>
                {{-- <a href="{{ route('laporan.exportListTransaction', [$tanggalAkhir]) }}" target="_blank" class="btn btn-success btn-block btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a> --}}
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="10%">Date</th>
                        <th>Nomor Nota</th>
                        <th>Tipe Customer</th>
                        <th>Customer</th>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>kategori</th>
                        <th>Supplier</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        {{-- <th>Diskon</th>
                        <th>Tambahan</th>
                        <th>Grand Diskon</th> --}}
                        <th>Total</th>
                        <th>HPP</th>
                        {{-- <th>Catatan</th>
                        <th>Metode Pembayaran</th>
                        <th>Ongkir</th>
                        <th>Jenis Transaksi</th> --}}
                        <th>No. HP</th>
                        <th>Pegawai</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- @includeIf('laporan.form_harian') --}}
@includeIf('laporan.form_day_cart')
@includeIf('pembelian.detail')

@endsection

@push('scripts')
<script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script> --}}
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script>
    
    
    
    let table, table1;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('laporan.detail_day_cart', [$tanggalAkhir]) }}',
            },
            columns: [
                {data: 'created_at', searchable: false, sortable: false},
                {data: 'nomor_nota'},
                {data: 'customer_type'},
                {data: 'customer'},
                {data: 'product_sku'},
                {data: 'product_title'},
                {data: 'category'},
                {data: 'supplier'},
                {data: 'base_price'},
                {data: 'count'},
                // {data: 'discount'},
                // {data: 'tambahan'},
                // {data: 'grand_discount'},
                {data: 'final_price'},
                {data: 'hpp'},
                // {data: 'catatan'},
                // {data: 'payment_method'},
                // {data: 'ongkir'},
                // {data: 'tipe_transaksi'},
                {data: 'no_hp'},
                {data: 'pegawai'},
                // {data: 'updated_at'},
                // {data: 'updated_at'},

            ],
            dom: 'Brt',
            bSort: false,
            bPaginate: false,
            
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

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
                {data: 'diskon'},

            ]
        })

    });

    function updatePeriode() {
        $('#modal-form_day_cart').modal('show');
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');
        console.log(url);
        table1.ajax.url(url);
        table1.ajax.reload();
    }

     
    $(document).ready(function () {
        $('#table-pembelian').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });
    });

</script>
@endpush