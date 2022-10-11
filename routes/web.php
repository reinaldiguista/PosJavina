<?php

use App\Http\Controllers\{
    CartController,
    DashboardController,
    LaporanController,
    ProdukController,
    MemberController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    SettingController,
    UserController,
    DiskonController,
    JobApiController,
    InvoiceController,

};
use App\Models\Cart;
use App\Models\JobApi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
      

        
        //PENJUALAN ROUTE

        //TRANSAKSI.VIEW (SPV, KSR)
        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        
        //TRANSAKSI.VIEW (SPV, KSR)
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');

        //STOP

       
        //TRANSAKSI.FULL (KSR)
        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        

        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        
        
        //TRANSAKSI

        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/{id}/create', [PenjualanController::class, 'create'])->name('transaksi.create');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');
    });

    Route::group(['middleware' => 'level:1'], function () {
        
        //LAPORAN (SPV, KSR)

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        //USER 

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    
    
    });
 
    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');

         //DISKON FULL (SPV)

         Route::get('/diskon/data', [DiskonController::class, 'data'])->name('diskon.data');
         Route::resource('/diskon', DiskonController::class);
    });

    Route::group(['middleware' => 'level:1,2,3,4'], function () {
        //CART ROUTE FULL (SPV, KSR, PDM)
        Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('/invoice/data', [InvoiceController::class, 'data'])->name('invoice.data');
        Route::get('/invoice/cancel', [InvoiceController::class, 'cancel'])->name('invoice.cancel');
        Route::get('/invoice/detail', [InvoiceController::class, 'detail'])->name('invoice.detail');
        Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
        Route::get('/invoice/create/{customer_id}/{id_pembelian}/{total}', [InvoiceController::class, 'create'])->name('invoice.create');

        //job
          Route::get('/job_api', [JobApiController::class, 'index'])->name('job_api.index');
          Route::get('/job_api/data', [JobApiController::class, 'data'])->name('job_api.data');
          Route::get('/job_api/sync/{sku}', [JobApiController::class, 'sync'])->name('job_api.sync');
          Route::get('/job_api/sync_all', [JobApiController::class, 'sync_all'])->name('job_api.sync_all');
          Route::get('/job_api/cek_sync', [JobApiController::class, 'cek_sync'])->name('job_api.cek_sync');
          Route::get('/job_api/local_product', [JobApiController::class, 'local_product'])->name('job_api.local_product');
          
          Route::get('/job_api/local_data', [JobApiController::class, 'local_data'])->name('job_api.local_data');
          Route::get('/job_api/isitaman_data', [JobApiController::class, 'isitaman_data'])->name('job_api.isitaman_data');
          
          Route::get('/job_api/stock/{id}', [JobApiController::class, 'stock'])->name('job_api.stock');
          Route::get('/job_api/check_all', [JobApiController::class, 'check_all'])->name('job_api.check_all');
          Route::get('/job_api/sync_stock', [JobApiController::class, 'sync_stock'])->name('job_api.sync_stock');
          Route::get('/job_api/sync_each/{sku}', [JobApiController::class, 'sync_each'])->name('job_api.sync_each');

        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);
        
        
        Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
        Route::get('/cart/new', [CartController::class, 'new'])->name('cart.new');
        Route::get('/cart/produk', [CartController::class, 'produk'])->name('cart.produk');    
        Route::delete('/cart/purge_send/{id}', [CartController::class, 'purge_send'])->name('cart.purge_send');
        Route::delete('/cart/purge_cart/{id}', [CartController::class, 'purge_cart'])->name('cart.purge_cart');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::get('/cart/{id}/creates', [CartController::class, 'create'])->name('cart.create');
        Route::get('/cart/show_send/{id}', [CartController::class, 'show_send'])->name('cart.show_send');
        Route::get('/cart/show_cart/{id}', [CartController::class, 'show_cart'])->name('cart.show_cart');
        Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
        Route::post('/cart/store/{customer_id}', [CartController::class, 'store'])->name('cart.store');

        Route::post('/cart/addProduct/{sku}/{count}/{customer_id}', [CartController::class, 'addProduct'])->name('cart.addProduct');
        Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::get('/cart/checkout/{id}', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
        
        Route::get('/cart/sku/{sku}', [CartController::class, 'sku'])->name('cart.sku');

        //cart full
        //customer full
        //PRODUK.FULL (ADM)
        //KHUSUS.VIEW CUMA (SPV, KSR, PDM)
        Route::get('/produk/index_view', [ProdukController::class, 'index_view'])->name('produk.index_view');
        Route::get('/produk/only_produk', [ProdukController::class, 'only_produk'])->name('produk.only_produk');
        Route::get('/produk/show_detail/{id}', [ProdukController::class, 'show_detail'])->name('produk.show_detail');
        Route::get('/produk/sku/{sku}', [ProdukController::class, 'sku'])->name('produk.sku');
        Route::get('/produk/send_stock/{sku}/{count}', [ProdukController::class, 'send_stock'])->name('produk.send_stock');
        Route::get('/produk/remove_stock/{sku}/{count}', [ProdukController::class, 'remove_stock'])->name('produk.remove_stock');

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        // Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);

    });

    Route::group(['middleware' => 'level:1,2,3'], function () {
        Route::get('/pembelian/only_transaksi', [PembelianController::class, 'only_transaksi'])->name('pembelian.only_transaksi');

        
        Route::get('/pembelian/list-cart', [PembelianController::class, 'list_cart'])->name('pembelian.list_cart');
        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/nota', [PembelianController::class, 'nota'])->name('pembelian.nota');
        Route::get('/pembelian/each_nota/{id}', [PembelianController::class, 'each_nota'])->name('pembelian.each_nota');
        Route::get('/pembelian/refund/{id}', [PembelianController::class, 'refund'])->name('pembelian.refund');
        Route::get('/pembelian/bug', [PembelianController::class, 'bug'])->name('pembelian.bug');

        Route::get('/pembelian/selesai', [PembelianController::class, 'selesai'])->name('pembelian.selesai');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::resource('/pembelian', PembelianController::class)
            ->except('create');     
        Route::get('/pembelian/nota-kecil', [PembelianController::class, 'notaKecil'])->name('pembelian.nota_kecil');
        // Route::get('/pembelian/member', [PembelianController::class, 'member'])->name('pembelian.member');

        Route::get('/pembelian_detail/nota-kecil', [PembelianDetailController::class, 'notaKecil'])->name('pembelian_detail.notaKecil');
        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::post('/pembelian_detail/addProduct/{sku}/{count}/{customer_id}', [PembelianDetailController::class, 'addProduct'])->name('pembelian_detail.addProduct');

        Route::post('/pembelian_detail/counter/{id}', [PembelianDetailController::class, 'counter'])->name('pembelian_detail.counter');
        Route::post('/pembelian_detail/case/{id}/{case}', [PembelianDetailController::class, 'case'])->name('pembelian_detail.case');

        Route::get('/pembelian_detail/kembalian/{kembalian}/{total}', [PembelianDetailController::class, 'kembalian'])->name('pembelian_detail.kembalian');
        Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');    });

    Route::group(['middleware' => 'level:1,3'], function () {
       //transaksi full
    });

    Route::group(['middleware' => 'level:1,2,3'], function () {
       //view tranksaksi
    });

Route::group(['middleware' => 'level:1,2'], function () {
       //full akses
    });
});