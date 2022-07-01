@extends('layouts.master')

@section('title')
    Transaksi Pembelian
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
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
                        <td>Supplier</td>
                        <td>: {{ $member->name }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: {{ $member->phone }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">

                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Masukkan PIN</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pin" id="pin">
                                <span class="input-group-btn">
                                    <button onclick="supevisor()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onclick="percent()" class="btn btn-secondary active">
                              <input type="radio" name="options1" id="option1" autocomplete="off" checked> Percent
                            </label>
                            </div>
                        </th>
                        <th>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label onclick="amount()" class="btn btn-secondary">
                                  <input type="radio" name="options2" id="option2" autocomplete="off"> Amount
                                </label>
                            </div>
                        </th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                            <input type="hidden" name="payment_status" value="sukses">
                            <input type="hidden" name="order_status" value="offline">
                            <input type="hidden" name="order_price" value=0>

                            <input type="hidden" name="total_price" id="total_price" value="{{ $total }}">

                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">

                            <div class="form-group row">
                                <label for="isSell" class="col-lg-4 control-label">is Sell ?</label>
                                <div class="col-lg-6">
                                    <select name="isSell" id="isSell" class="form-control">
                                        <option value=0>Jual</option>
                                        <option value=1>Beli</option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" value=0>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="order_price" class="col-lg-2 control-label">Order Price</label>
                                <div class="col-lg-8">
                                    <input type="text" id="order_price" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="payment_method" class="col-lg-4 control-label">Metode Pembayaran</label>
                                <div class="col-lg-6">
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="Tunai">Tunai</option>
                                        <option value="Qris">Qris</option>
                                        <option value="Rekening">Rekening</option>
                                        <option value="Utang">Utang</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="total_price" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="number" class="col-lg-2 control-label">Number</label>
                                <div class="col-lg-8">
                                    <input name=number type="text" id="number" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('pembelian_detail.produk')
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
                url: '{{ route('pembelian_detail.data', $id_pembelian) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'sku'},
                {data: 'title'},
                {data: 'base_price'},
                {data: 'count'},
                {data: 'diskon_percent'},
                {data: 'diskon_amount'},
                {data: 'final_price'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })

        .on('draw.dt', function () {
            loadForm($('#diskon').val());
        });

        table2 = $('.table-produk').DataTable();

        $(document).on('input', '.discount_percent', function () {
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

        $(document).on('input', '.discount_amount', function () {
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

        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });
    });


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

    function percent() {
        $('.discount_amount').hide();
        $('.discount_percent').show();

    }

    function amount() {
        $('.discount_percent').hide();
        $('.discount_amount').show();
    }

    function loadForm(diskon = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon}/${$('.total').text()}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#bayarrp').val('Rp. '+ response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Rp. '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }
</script>
@endpush