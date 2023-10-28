@extends('layouts.master')

@section('title')
    Tabel Harga
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Tabel harga</li>
@endsection



@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('price.store') }}')" class="btn btn-success btn-lg btn-block"><i class="fa fa-plus-circle"></i> Tambah</button>
                <br>
                <a href="{{ route('export_excel.export_price') }}" class="btn btn-success">EXPORT</a>
                {{-- <a href="{{ route('export_excel.export') }}" class="btn btn-success">IMPORT</a> --}}
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> IMPORT</a>
                
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th>Kode Produk</th>
                            <th>Harga 1</th>
                            <th>Harga 2</th>
                            <th>Harga 3</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<div>
    
      
      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Import Produk</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('export_excel.import_price') }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="file" name="file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
          </div>
        </div>
      </div>
</div>

@includeIf('price.form')
@includeIf('price.edit')
@includeIf('produk.detail')
@includeIf('produk.stock')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('price.data') }}',
            },
            columns: [
                {data: 'sku'},
                {data: 'harga_1'},
                {data: 'harga_2'},
                {data: 'harga_3'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $(document).ready(function () {
            $("#sku_produk").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                autoWidth: true

            });
            $("#sku_produk_edit").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                autoWidth: true

            });
        })


        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Harga');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=title]').focus();
    }

    function showDetail(url) {
        console.log(url);
        $('#modal-detail').modal('show');

        $.get(url)
            .done((response) => {
                $('#modal-detail [name=jenis_produk]').val(response.jenis_produk);
                $('#modal-detail [name=kondisi]').val(response.kondisi);
                $('#modal-detail [name=genus]').val(response.genus);
                $('#modal-detail [name=supplier]').val(response.supplier);
                $('#modal-detail [name=registrasi_anggrek]').val(response.registrasi_anggrek);
                $('#modal-detail [name=grade]').val(response.grade);
                $('#modal-detail [name=hb_sp]').val(response.hb_sp);

            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function editForm(url) {
        console.log(url);
        $('#modal-edit').modal('show');
        $('#modal-edit .modal-title').text('Edit Produk');

        $('#modal-edit form')[0].reset();
        $('#modal-edit form').attr('action', url);
        $('#modal-edit [name=_method]').val('put');
        $('#modal-edit [name=title]').focus();

        $.get(url)
            .done((response) => {
                console.log(response.sku_produk);
                $('#modal-edit [name=sku_produk]').val(response.sku_produk);
                $('#modal-edit [name=harga_1]').val(response.harga_1);
                $('#modal-edit [name=harga_2]').val(response.harga_2);
                $('#modal-edit [name=harga_3]').val(response.harga_3);
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
                if (response.status == "fail") {
                    alert("data tidak ditemukan di isi taman, yakin ingin melanjutkan transaksi ?");
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    $('#modal-stock').modal('hide');
                    table.ajax.reload();                    
                } else if (response.status == "fail_produk") {
                    alert("data produk tidak ditemukan di isi taman, data local ditampilkan");
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    table.ajax.reload();
                } else if (response.status == "fail_job") {
                    alert("data produk belum sinkron dengan isiTaman, data local ditampilkan");
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    table.ajax.reload();
                } else if (response.status == "local_product") {
                    // alert("product Local");
                    $('#sku_produk').val(response.sku);
                    $('#stock_produk').val(response.stock);
                    table.ajax.reload();
                } else {
                alert("sukses");
                $('#sku_produk').val(response.sku);
                $('#stock_produk').val(response.stock);
                table.ajax.reload();    
                }                    
            })
            .fail(errors => {
                alert('Terjadi Kesalahan di server, cek sinkronasi produk terlebih dahulu ');
                $('#modal-stock').modal('hide');
                table.ajax.reload();                    
                return;
            })

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
                if (response.status == "fail") {
                    alert("data tidak ditemukan di isi taman, yakin ingin melanjutkan transaksi ?");
                    $('#produk_kembali').val('');  
                    $('#modal-stock').modal('hide');
                    $('#modal-stock').modal('hide');
                    table.ajax.reload();                    
                } else if (response.status == "fail_produk") {
                    alert("data produk tidak ditemukan di isi taman, data local ditampilkan");
                    $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                    table.ajax.reload();
                } else if (response.status == "fail_job") {
                    alert("data produk belum sinkron dengan isiTaman, data local berhasil ditambah");
                    $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                    table.ajax.reload();
                } else if (response.status == "local_product"){
                    // alert("product local");
                $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                table.ajax.reload();    
                } else {
                alert("sukses");
                $('#produk_kembali').val('');  
                $('#modal-stock').modal('hide');
                table.ajax.reload();  
                }                            
            })
            .fail(errors => {
                alert('Terjadi Kesalahan di server atau data produk tidak ditemukan di isiTaman');
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
                if (response.status == 'fail_stok') {
                    alert('Jumlah Melebihi Stok');
                } else {
                    $('#produk_kembali').val('');  
                    $('#modal-stock').modal('hide');
                    table.ajax.reload();       
                }
            })
            .fail(errors => {
                alert('Terjadi Kesalahan di server');
                $('#modal-stock').modal('hide');
                table.ajax.reload();                    

                return;
            })

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

