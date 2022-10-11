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
                <h2>Anda login sebagai PENDAMPING</h2>
                <br>
                <a href="{{ route('member.index') }}" class="btn btn-primary btn-lg btn-block">List Customer</a>
                <br>
                <button onclick="addCart()" class="btn btn-warning btn-lg btn-block"><i class="fa fa-plus-circle"></i> Cart Baru</button>
                <br>
                <a href="{{ route('cart.index') }}" class="btn btn-success btn-lg btn-block">List Cart</a>
                <br>
                <a href="{{ route('produk.only_produk') }}" class="btn btn-danger btn-lg btn-block">List Product</a>
                <br>
            </div>
        </div>
    </div>
</div>
<!-- /.row (main row) -->
@includeIf('cart.customer')

@endsection

@push('scripts')
    <script>
    
    $('.table-customer').DataTable({
        
            });


    function addCart() {
        $('#modal-customer').modal('show');
    }
    
    </script>
@endpush