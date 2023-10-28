<link rel="stylesheet" href="css/bootstrap.css">
 

<!-- Left side column. contains the logo and sidebar -->
<aside  class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div onload="cek_sync()" class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                {{-- <button onclick="bug()" class="btn btn-primary btn-xs"><i class="fa fa-handshake-o"></i> Sync all</button>
                <i id="check" name="check" style="display: none; float:right;" class="fa fa-check-circle text-success"></i>
                <i id="times" name="times" style="display: none; float: right;" class="fa fa-times-circle text-danger"></i> --}}
            </div>
        </div>
        
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            {{-- <li>
                <a href="{{ route('export_excel.index') }}">
                    <i class="fa fa-file-excel-o"></i> <span>Export Produk</span>
                </a>
            </li> --}}
        @if (auth()->user()->level == 1)
            <li class="header">MASTER PRODUK</li>
            
            {{-- <li>
                <a href="{{ route('import-view') }}">
                    <i class="fa fa-cubes"></i> <span>Replace Stock</span>
                </a>
            </li> --}}
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i> <span>Produk</span>
                </a>
            </li>
            {{-- <li>
                <a href="{{ route('export_excel.index') }}">
                    <i class="fa fa-file-excel-o"></i> <span>Export Produk</span>
                </a>
            </li> --}}
            <li>
                <a href="{{ route('price.index') }}">
                    <i class="fa fa-file-excel-o"></i> <span>Export Price</span>
                </a>
            </li>
            <li>
                <a href="{{ route('price_rules.index') }}">
                    <i class="fa fa-file-excel-o"></i> <span>Export Price Rules</span>
                </a>
            </li>

            {{-- <li>
                <a href="{{ route('stock.index') }}">
                    <i class="fa fa-download"></i> <span>Stok Barang</span>
                </a>
            </li> --}}
            
            {{-- <li>
                <a href="{{ route('produk.stock_replace') }}">
                    <i class="fa fa-handshake-o"></i> <span>Stok Replace</span>
                </a>
            </li> --}}
            {{-- <li class="header">SINKRONISASI PRODUK</li> --}}
            {{-- <li>
                <a href="{{ route('job_api.index') }}">
                    <i class="fa fa-cube"></i> <span>Produk Not Sync</span>
                </a>
            </li>

            <li>
                <a href="{{ route('job_api.local_product') }}">
                    <i class="fa fa-database"></i> <span>Produk Local</span>
                </a>
            </li> --}}

            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Customer</span>
                </a>
            </li>
            <li class="header">TRANSAKSI</li>
            <li>
                <a  href="{{ route('cart.index') }}">
                    <i class="fa fa-superpowers"></i> <span>ALL</span>
                </a>
            </li>
            <li>
                <a  href="{{ route('cart.index_kasir') }}">
                    <i class="fa fa-money"></i> <span>Transaksi</span>
                </a>
            </li>

            <li>
                <a  href="{{ route('cart.index_staff') }}">
                    <i class="fa fa-shopping-cart"></i> <span>Keranjang</span>
                </a>
            </li>
            
            <li>
                <a onclick="bug()" href="{{ route('pembelian.index') }}">
                    <i class="fa fa-upload"></i> <span>Riwayat Transaksi</span>
                </a>
            </li>
            {{-- <li>
                <a  href="{{ route('invoice.index') }}">
                    <i class="fa fa-credit-card"></i> <span>Invoice</span>
                </a>
            </li>
            <li>
                <a onclick="bug()" href="{{ route('pembelian.order_online') }}">
                    <i class="fa fa-upload"></i> <span>Order Online</span>
                </a>
            </li> --}}
            
            {{-- <li>
                <a href="{{ route('pembelian_detail.index') }}">
                    <i class="fa fa-cart-plus"></i> <span>Transaksi Aktif</span>
                </a>
            </li> --}}
            <li class="header">REPORT</li>
            
            {{-- <li>
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Per Tanggal</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.harian_index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Per Hari</span>
                </a>
            </li> --}}
            <li class="treeview">
                <a href="#">
                  <i class="fa fa-database"></i> <span>Harian</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="{{ route('laporan.day_cart') }}"><i class="fa fa-file-pdf-o"></i> Detail Cart Harian</a></li>
                  <li><a href="{{ route('laporan.detail_day') }}"><i class="fa fa-file-pdf-o"></i> Detail Hari</a></li>
                  <li><a href="{{ route('laporan.day_order') }}"><i class="fa fa-file-pdf-o"></i> Harian by Order</a></li>
                  <li><a href="{{ route('laporan.day_payment') }}"><i class="fa fa-file-pdf-o"></i> Harian by Payment</a></li>
                </ul>
            </li>

            {{-- <li>
                <a href="{{ route('laporan.day_cart') }}">
                    <i class="fa fa-database"></i> <span>Detail Cart Harian</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.detail_day') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Detail Hari</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.day_order') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Harian by Order</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.day_payment') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Harian by Payment</span>
                </a>
            </li> --}}
            <li class="header">SYSTEM</li>
            <li>
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users"></i> <span>User</span>
                </a>
            </li>
            {{-- <li>
                <a href="{{ route('diskon.index') }}">
                    <i class="fa fa-percent"></i> <span>Diskon</span>
                </a>
            </li> --}}
            
        
        @elseif(auth()->user()->level == 2)

        <li class="header">MASTER</li>

            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i> <span>Produk</span>
                </a>
            </li>

            {{-- <li>
                <a href="{{ route('job_api.index') }}">
                    <i class="fa fa-cube"></i> <span>Produk Not Sync</span>
                </a>
            </li> --}}

            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Customer</span>
                </a>
            </li>

        <li class="header">TRANSAKSI</li>
            <li>
                <a href="{{ route('cart.index') }}">
                    <i class="fa fa-shopping-cart"></i> <span>Daftar Cart</span>
                </a>
            </li>

            <li>
                <a onclick="bug()" href="{{ route('pembelian.only_transaksi') }}">
                    <i class="fa fa-upload"></i> <span>List Transaksi</span>
                </a>
            </li>

        {{-- <li class="header">REPORT</li>
            <li>
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan</span>
                </a>
            </li> --}}

            {{-- <li class="header">SYSTEM</li>
                <li>
                    <a href="{{ route('diskon.index') }}">
                        <i class="fa fa-percent"></i> <span>Diskon</span>
                    </a>
                </li> --}}
        
        
        @elseif(auth()->user()->level == 3)

        <li class="header">MASTER</li>

            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i> <span>Produk</span>
                </a>
            </li>

            {{-- <li>
                <a href="{{ route('job_api.index') }}">
                    <i class="fa fa-cube"></i> <span>Produk Not Sync</span>
                </a>
            </li> --}}

            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Customer</span>
                </a>
            </li>
        
        
        <li class="header">TRANSAKSI</li>
            {{-- <li>
                <a href="{{ route('cart.index') }}">
                    <i class="fa fa-shopping-cart"></i> <span>Daftar Cart</span>
                </a>
            </li> --}}

            <li>
                <a  href="{{ route('cart.index') }}">
                    <i class="fa fa-shopping-cart"></i> <span>Keranjang</span>
                </a>
            </li>

            <li>
                <a onclick="bug()" href="{{ route('pembelian.index') }}">
                    <i class="fa fa-upload"></i> <span>List Transaksi</span>
                </a>
            </li>
            {{-- <li>
                <a onclick="bug()" href="{{ route('pembelian.order_online') }}">
                    <i class="fa fa-upload"></i> <span>Order Online</span>
                </a>
            </li> --}}
            {{-- <li>
                <a  href="{{ route('invoice.index') }}">
                    <i class="fa fa-credit-card"></i> <span>Invoice</span>
                </a>
            </li> --}}
        
        <li class="header">REPORT</li>
           

            <li class="treeview">
                <a href="#">
                  <i class="fa fa-database"></i> <span>Harian</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="{{ route('laporan.day_cart') }}"><i class="fa fa-file-pdf-o"></i> Detail Cart Harian</a></li>
                  <li><a href="{{ route('laporan.detail_day') }}"><i class="fa fa-file-pdf-o"></i> Detail Hari</a></li>
                  <li><a href="{{ route('laporan.day_order') }}"><i class="fa fa-file-pdf-o"></i> Harian by Order</a></li>
                  <li><a href="{{ route('laporan.day_payment') }}"><i class="fa fa-file-pdf-o"></i> Harian by Payment</a></li>
                </ul>
            </li>
        
        
        @elseif(auth()->user()->level == 4)
        <li class="header">MASTER</li>
        <li>
            <a href="{{ route('produk.index') }}">
                <i class="fa fa-cubes"></i> <span>Produk</span>
            </a>
        </li>

        {{-- <li>
            <a href="{{ route('job_api.index') }}">
                <i class="fa fa-cube"></i> <span>Produk Not Sync</span>
            </a>
        </li> --}}
        
        <li>
            <a href="{{ route('member.index') }}">
                <i class="fa fa-id-card"></i> <span>Customer</span>
            </a>
        </li>

    <li class="header">TRANSAKSI</li>
        
        <li>
            <a href="{{ route('cart.index_staff') }}">
                <i class="fa fa-shopping-cart"></i> <span>Daftar Cart</span>
            </a>
        </li>

        
        @endif

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

@push('scripts')
    <script>
    
    function cek_sync() {
        // document.getElementById('times').style.display = "none";
        // document.getElementById('check').style.display = "none";
            $.get(`{{ url('/job_api/cek_sync') }}`, {
                    // '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    if (response.status == 'success') {
                        document.getElementById('times').style.display = "none";
                        document.getElementById('check').style.display = "block";

                    } else {
                        document.getElementById('times').style.display = "block";
                        document.getElementById('check').style.display = "none";
                    }
                    table.ajax.reload();  
                })
                .fail(errors => {
                });
        }

        function snyc() {
            $.get(`{{ url('/job_api/sync_all') }}`, {
                    // '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    if (response.status == 'sukses') {
                        alert('success');

                    } else {
                        // alert('terjadi kegagalan');
                    }
                    table.ajax.reload();                    
                })
                .fail(errors => {
                });
        }

        function bug() {
            console.log('bug');
            $.get(`{{ url('/pembelian/bug') }}`, {
                    // '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'get'
                })
                .done(response => {
                    if (response.status == 'sukses') {
                        alert('success');

                    } else {
                        // alert('terjadi kegagalan');
                    }
                    table.ajax.reload();                    
                })
                .fail(errors => {
                });
            }

    </script>
@endpush