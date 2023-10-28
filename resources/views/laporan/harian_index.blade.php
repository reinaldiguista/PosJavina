@extends('layouts.master')

@section('title')
    Laporan Pendapatan Harian {{ tanggal_indonesia($tanggalAkhir) }}
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
                <a href="{{ route('laporan.exportListTransaction', [$tanggalAkhir]) }}" target="_blank" class="btn btn-success btn-block btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Nomor Transaksi</th>
                        <th>Jenis Transaksi</th>
                        <th>Metode Pembayaran</th>
                        <th>Total</th>
                        <th>Jumlah Barang</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                        <th>Sender</th>

                        {{-- <th>Pengeluaran</th> --}}
                        {{-- <th>Pendapatan</th> --}}
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('laporan.form_harian')
@includeIf('pembelian.detail')

@endsection

@push('scripts')
<script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script> --}}
<script>
    
    
    
    let table, table1;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('laporan.data_harian', [$tanggalAkhir]) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'number_ref'},
                {data: 'order_type'},
                {data: 'payment_method'},
                {data: 'penjualan'},
                {data: 'barang'},
                {data: 'aksi'},
                {data: 'sender'},
                // {data: 'pengeluaran'},
                // {data: 'pendapatan'}
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
                {data: 'base_price'},
                {data: 'jumlah'},
                {data: 'total_price'},
                {data: 'diskon'},
                {data: 'final_price'},
                {data: 'special_case'},

            ]
        })

    });

    function updatePeriode() {
        $('#modal-form').modal('show');
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');
        console.log(url);
        table1.ajax.url(url);
        table1.ajax.reload();
    }

     
    // $(document).ready(function () {
    //     $('#table-pembelian').DataTable({
    //         dom: 'Bfrtip',
    //         buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    //     });
    // });

</script>
@endpush