@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk</li>
@endsection



@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-lg btn-block"><i class="fa fa-plus-circle"></i> Tambah</button>
                <br>
                <a href="{{ route('export_excel.export') }}" class="btn btn-success">EXPORT</a>
                {{-- <a href="{{ route('export_excel.export') }}" class="btn btn-success">IMPORT</a> --}}
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> IMPORT</a>
                
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            {{-- <th>Online Price</th> --}}
                            <th>Harga Terendah</th>
                            <th>Stock</th>
                            <th>Enable/Disable</th>
                            {{-- <th>isSync</th> --}}
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
            <form action="{{ route('export_excel.import') }}" method="post" enctype="multipart/form-data">
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

@includeIf('produk.form')
@includeIf('produk.edit')
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
                url: '{{ route('produk.data') }}',
            },
            columns: [
                {data: 'sku'},
                {data: 'nama_produk'},
                {data: 'kategori'},
                {data: 'harga_terendah'},
                // {data: 'offline_price'},
                {data: 'stock'},
                {data: 'enable'},
                // {data: 'isSync'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });


        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });

        $(document).ready(function () {
            $("#kategori").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
            $("#jenis_produk").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true
                
            });
            $("#kondisi").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
            $("#genus").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
            $("#supplier").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
            $("#jenis_supplier").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
            $("#registrasi_anggrek").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
            $("#grade").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
            $("#hb_sp").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
            $("#kelompok_pasar").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
            $("#enable").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true,
                dropdownAutoWidth : true

            });
        // let x = $(this).val();
        // console.log(x);
        })
        
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Produk');

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
        $('#modal-edit').modal('show');
        $('#modal-edit .modal-title').text('Edit Produk');

        $('#modal-edit form')[0].reset();
        $('#modal-edit form').attr('action', url);
        $('#modal-edit [name=_method]').val('put');
        $('#modal-edit [name=title]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-edit [name=sku]').val(response.sku);
                $('#modal-edit [name=kategori]').val(response.kategori);
                $('#modal-edit [name=nama_produk]').val(response.nama_produk);
                $('#modal-edit [name=jenis_produk]').val(response.jenis_produk);
                $('#modal-edit [name=kondisi]').val(response.kondisi);
                $('#modal-edit [name=genus]').val(response.genus);
                $('#modal-edit [name=supplier]').val(response.supplier);
                $('#modal-edit [name=jenis_supplier]').val(response.jenis_supplier);
                $('#modal-edit [name=registrasi_anggrek]').val(response.registrasi_anggrek);
                $('#modal-edit [name=grade]').val(response.grade);
                $('#modal-edit [name=hb_sp]').val(response.hb_sp);
                $('#modal-edit [name=kelompok_pasar]').val(response.kelompok_pasar);
                $('#modal-edit [name=kode_kebun]').val(response.kode_kebun);
                $('#modal-edit [name=harga_terendah]').val(response.harga_terendah);
                $('#modal-edit [name=stock]').val(response.stock);
                $('#modal-edit [name=hpp]').val(response.hpp);
                $('#modal-edit [name=enable]').val(response.enable);
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

