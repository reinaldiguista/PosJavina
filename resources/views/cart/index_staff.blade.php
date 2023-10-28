@extends('layouts.master')

@section('title')
    Daftar Cart
    <title>Daftar Cart</title>

@endsection

@push('css')

<style>
 /* Customize the label (the container) */
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}   

input.largerCheckbox {
            width: 20px;
            height: 20px;
            text-align: center;
            /* center checkbox horizontally */
            vertical-align: middle;
            /* center checkbox vertically */        }
</style>

@endpush

@section('breadcrumb')
    @parent
    <li class="active"> Daftar Cart</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                {{-- <button onclick="addCart()" class="btn btn-success btn-lg btn-block"><i class="fa fa-plus-circle"></i> Cart Baru</button> --}}
                <div class="form-group row">
                    <label for="position-option" class="col-lg-1 control-label">Nama</label>
                        <div class="col-lg-3">
                            <select name="nama" id="nama" class="form-control">
                                @foreach ($customer as $key)
                                        <option value="{{ $key->id }}">{{ $key->name }}</option>
                                @endforeach
                            </select>
                        <div id="div_type" hidden="hidden">
                            <select name="type" id="type" class="form-control" hidden="hidden">
                                @foreach ($customer_type as $key)
                                    <option value="{{ $key->id }}">{{ $key->role }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="col-lg-2">
                            <a href="{{ route('cart.create_staff', 0) }}" onclick="cek_session()" class="btn btn-success btn-s" id="session" name="session">
                                <i class="fa fa-plus-circle"></i> Buat Cart
                            </a>
                        </div>
                </div>
                <div>
                    {{-- <a href="{{ route('cart.transaction_detail') }}" onclick="transaction('{{ route('cart.transaction') }}')" class="btn btn-info btn-lg btn-block" id="transaction" name="transaction">
                        <i class="fa fa-plus-circle"></i> Buat Transksi
                    </a> --}}
                    {{-- <a onclick="transaction('{{ route('cart.transaction') }}')" class="btn btn-info btn-lg btn-block" id="transaction" name="transaction">
                        <i class="fa fa-plus-circle"></i> Buat Transksi
                    </a>     --}}
                    {{-- <a href="{{ route('cart.transaction_detail') }}" class="btn btn-info btn-lg btn-block" id="transaction" name="transaction">
                        <i class="fa fa-plus-circle"></i> Buat Transksi
                    </a> --}}
                </div>

                {{-- <form action="{{ route('cart_master.file_import') }}" method="POST" enctype="multipart/form-data">
                    @csrf --}}
                    {{-- <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <button class="btn btn-primary">Import data</button> --}}
                    {{-- <a class="btn btn-success" href="{{ route('file-export') }}">Export data</a> --}}
                {{-- </form> --}}
                
            </div>
            <div class="box-body table-responsive">
                <form class="form-cart">
                    @csrf
                    <table class="table table-stiped table-bordered table-penjualan">
                        <thead> 
                            <th>
                                <input class="largerCheckbox" type="checkbox" name="select_all" id="select_all">
                            </th>
                            {{-- <th width="5%">No</th> --}}
                            <th>Nomor Nota</th>
                            <th>Nama Customer</th>
                            <th>Pegawai</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>     
                            {{-- <div class="action_btn">
                                <a href="{{ route('transaksi.index')}}" class="btn btn-warning">Edit</a>
                              </div>                    --}}
                        </thead>
                    </table>
                </form> 
            </div>
        </div>
    </div>
</div>

@includeIf('cart.detail')
@includeIf('cart.checkout')
@includeIf('cart.customer')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let table, table1;
    let customer_id;
    $(function () {
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: true,
            sortable: false,
            ajax: {
                url: '{{ route('cart.data_staff') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'nomor_nota'},
                {data: 'customer', sortable: false},
                {data: 'employee', sortable: false},
                // {data: 'isSend', sortable: false},
                {data: 'aksi', searchable: false, sortable: false},
            ],
        });

        $('.table-customer').DataTable({
        });


        table1 = $('.table-jancok').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'product_id'},
                {data: 'base_price'},
                {data: 'count'},
                {data: 'final_price'},
            ]
        })

        $('[name=select_all]').on('click', function () {
           $(':checkbox').prop('checked', this.checked); 
        })

        $(".form-cart").submit(function(e) {
            e.preventDefault();
        });

        $(document).ready(function () {
            $("#nama").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true

            });
            $("#type").select2({
                placeholder: 'Select an option',
                tags: true,
                allowClear: true

            });
        // let x = $(this).val();
        // console.log(x);
    })
    });


    
    function showDetail(url) {

        console.log(url);
        {{ route('cart.data') }}
        $('#modal-detail').modal('show');
        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function lanjut($url) {
        console.log($url);
        $.get($url)
            .done(response => {
                
            })
            .fail(errors => {
               
            })
    }
    
    function sendCart($id) {
        console.log($id);
        let customer_id = $id;
        $.post(`{{ url('/cart/store') }}/${customer_id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post',
                    'customer_id': customer_id
                })
                .done(response => {
                    table.ajax.reload();                    
                })
                .fail(errors => {
                });
    }


    function addCart() {
        $('#modal-customer').modal('show');
    }


    function checkout(url) {
        $('#modal-checkout').modal('checkout');
        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url, id) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete',
                })
                .done(response => {
                    if (response.status == 'local_product_purge') {
                        alert('data local berhasil dihapus');
                        table.ajax.reload();                    
                    } else if (response.status == 'fail_job_purge'){
                        alert('produk belum sinkron, data berhasil dihapus');
                        table.ajax.reload(); 
                    } else if (response.status == 'fail_connect'){
                        alert('Terjadi kesalahan dengan server, data berhasil di hapus');
                        table.ajax.reload(); 
                    }
                    else {
                        // alert('');
                        table.ajax.reload();                    
                    }                 
                })
                .fail(errors => {
                    alert('data berhasil terhapus dari local, namun belum sinkron');
                    table.ajax.reload();                    
                });
        }
    }

    function transaction(url) {
        if ($('input:checked').length > 0 ) {
            if (confirm('Apakah anda yakin dengan pilihan anda ?')) {
                console.log(url);
                $.post(url, $('.form-cart').serialize())
                    .done((response) => {

                    })
                    .fail((errors) => {
                        alert('Tidak dapat membuat transaksi');
                        
                    })
            }
        } else {
            alert('pilih cart dahulu anjeng');
            // var href = $("#transaction").attr('href');
            // console.log(href);
            $( "#transaction" ).removeAttr('href');
            location.reload()
            return;
        }
    }

    function cek_session(url) {         
        let element = document.getElementById("div_type");
        let hidden = element.getAttribute("hidden");
        let x = 0;

        if (hidden) {
            if (confirm('Pilih User Dulu Anjeng')) {
                $( "#session" ).removeAttr('href');
                location.reload()
                return;
                
            } else {
                $( "#session" ).removeAttr('href');
                location.reload()
                return;
            }
        } else {
            
        }
    }

    $(document).on('input', '#nama', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            let x = $(this).val();
            let text = $("#nama option:selected").text();

            let element = document.getElementById("div_type");
            let hidden = element.getAttribute("hidden");

            if (hidden) {
                element.removeAttribute("hidden");
            } else {
                // element.setAttribute("hidden", "hidden");
            }
            // console.log(x, text);
            loadName(x, text);
    });

    $(document).on('input', '#type', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            let x = $(this).val();
            let text = $("#type option:selected").text();

            loadType(x, text);
    });

    function loadName(x, text) {
        let before = $('#name_id').val();
        
        $('#name_id').val(x);
        // console.log(before);
        let after = $('#name_id').val();

        // console.log(after);
        $.get(`{{ url('/cart/sess_name') }}/${x}/${text}`)
            .done(response => {
                $('#customer_id').val(response.customer_id);
                $('#name_id').val(response.customer_id);

                console.log(response.customer_id);
            })
            .fail(errors => {
                // alert('Tidak dapat menampilkan data');
                return;
            })
    }

    function loadType(x, text) {
        let before = $('#name_id').val();
        
        $('#name_id').val(x);
        // console.log(before);
        let after = $('#name_id').val();

        // console.log(after);
        $.get(`{{ url('/cart/loadType') }}/${x}/${text}`)
            .done(response => {
                // $('#customer_id').val(response.customer_id);
                // $('#name_id').val(response.customer_id);

                console.log(response.customer_id);
            })
            .fail(errors => {
                // alert('Tidak dapat menampilkan data');
                return;
            })
    }
</script>
@endpush