@extends('layouts.master')

@section('title')
    Daftar Invoice
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Invoice</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                {{-- <button onclick="addForm('{{ route('invoice.store') }}')" class="btn btn-success btn-lg btn-block"><i class="fa fa-plus-circle"></i> Buat Invoice</button> --}}
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-member">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Nomor Invoice</th>
                            <th>Customer</th>
                            <th>Nomor Transaksi </th>
                            <th>Total Invoice</th>
                            <th>Sisa Terhutang</th>
                            <th>Tanggal Dibuat</th>
                            <th>Tenggat Waktu</th>
                            <th>Status</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('invoice.form')
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
                url: '{{ route('invoice.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'no_invoice'},
                {data: 'customer'},
                {data: 'number_ref'},
                {data: 'amount'},
                {data: 'debt'},
                {data: 'created_at'},
                {data: 'due_date'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Buat Invoice');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }

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