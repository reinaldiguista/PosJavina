@extends('layouts.master')

@section('title')
    List Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">List Produk</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-lg btn-block"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th>Kode</th>
                            <th>Title</th>
                            <th>stock</th>
                            {{-- <th>Online Price</th>
                            <th>Offline price</th>
                            <th>Agen price</th>
                            <th>Reseller_price</th>
                            <th>no. meja</th> --}}
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('produk.form')
@includeIf('produk.stock')
@includeIf('produk.detail')
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
                url: '{{ route('produk.data_cepat') }}',
            },
            columns: [
                {data: 'kode_produk'},
                {data: 'title'},
                {data: 'stock'},
                // {data: 'price'},
                // {data: 'offline_price'},
                // {data: 'agen_price'},
                // {data: 'reseller_price'},
                // {data: 'nomor_meja'},
                {data: 'cek_stok'},
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
        $('#modal-form .modal-title').text('Tambah Produk');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=title]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Produk');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=title]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=title]').val(response.title);
                $('#modal-form [name=sku]').val(response.sku);
                $('#modal-form [name=desc]').val(response.desc);
                $('#modal-form [name=length]').val(response.length);
                $('#modal-form [name=width]').val(response.width);
                $('#modal-form [name=height]').val(response.height);
                $('#modal-form [name=volmetric]').val(response.volmetric);
                $('#modal-form [name=price]').val(response.price);
                $('#modal-form [name=agen_price]').val(response.agen_price);
                $('#modal-form [name=reseller_price]').val(response.reseller_price);
                $('#modal-form [name=offline_price]').val(response.offline_price);
                $('#modal-form [name=handling_fee]').val(response.handling_fee);
                $('#modal-form [name=stock]').val(response.stock);
                $('#modal-form [name=stock_offline]').val(response.stock_offline);
                $('#modal-form [name=discount]').val(response.discount);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
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

    function deleteSelected(url) {
        if ($('input:checked').length > 1) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, $('.form-produk').serialize())
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        } else {
            alert('Pilih data yang akan dihapus');
            return;
        }
    }

    function cekStok($sku) {
        $('#stock_produk').val('');
        $('#sku_produk').val('');
        $('#modal-stock').modal('show');
        console.log($sku);
        let sku = $sku
        $.get($sku)
            .done(response => {
                console.log(response.sku);
                $('#sku_produk').val(response.sku);
                $('#stock_produk').val(response.stock);
                table.ajax.reload();                    
            })
            .fail(errors => {
                alert('Terjadi Kesalahan di server, cek sinkronasi produk terlebih dahulu ');
                $('#modal-stock').modal('hide');
                table.ajax.reload();                    
                return;
            })
    }

    function showDetail(url) {
        console.log(url);
        $('#modal-detail').modal('show');

        $.get(url)
            .done((response) => {
                $('#modal-detail [name=kategori]').val(response.kategori);
                $('#modal-detail [name=volmetric]').val(response.volmetric);
                $('#modal-detail [name=desc]').val(response.desc);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
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
                $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                table.ajax.reload();                    
            })
            .fail(errors => {
                alert('Terjadi Kesalahan di server, kamu yang benar');
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
                $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                table.ajax.reload();       
            })
            .fail(errors => {
                alert('Terjadi Kesalahan di server');
                $('#modal-stock').modal('hide');
                table.ajax.reload();                    

                return;
            })

    }

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Produk');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=title]').focus();
    }
    // function cetakBarcode(url) {
    //     if ($('input:checked').length < 1) {
    //         alert('Pilih data yang akan dicetak');
    //         return;
    //     } else if ($('input:checked').length < 3) {
    //         alert('Pilih minimal 3 data untuk dicetak');
    //         return;
    //     } else {
    //         $('.form-produk')
    //             .attr('target', '_blank')
    //             .attr('action', url)
    //             .submit();
    //     }
    // }
</script>
@endpush