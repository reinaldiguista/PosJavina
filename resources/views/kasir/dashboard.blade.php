@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body text-center">
                <h1>Selamat Datang</h1>
                <h2>Anda login sebagai KASIR</h2>
                <br><br>
                {{-- <button onclick="addForm()" class="btn btn-info btn-lg btn-block"><i></i> Transaksi Baru</button> --}}
                <br>
                <a onclick="bug()" href="{{ route('pembelian.index') }}" class="btn btn-primary btn-lg btn-block">List Transaksi</a>
                <br>
                <a href="{{ route('cart.index') }}" class="btn btn-success btn-lg btn-block">Cart</a>
                <br>
                <a href="{{ route('produk.index') }}" class="btn btn-danger btn-lg btn-block">List Product</a>
                <br>
                <a href="{{ route('member.index') }}" class="btn btn-warning btn-lg btn-block">Customer</a>
                <br>
            </div>
        </div>
    </div>
</div>
<!-- /.row (main row) -->

@includeIf('pembelian.cart')
@includeIf('pembelian.detail')

@endsection

@push('scripts')
    <script>
    
    function addForm() {
        $('#modal-cart').modal('show');
    }
    
    function bug() {
            $.get(`{{ url('/pembelian/bug') }}`, {
                    // '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    if (response.status == 'sukses') {
                        alert('success');

                    } else {
                        alert('terjadi kegagalan');
                    }
                    table.ajax.reload();                    
                })
                .fail(errors => {
                });
            }
    </script>
@endpush