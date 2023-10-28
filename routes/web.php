<?php

use App\Http\Controllers\{
    CartController,
    DashboardController,
    LaporanController,
    ProdukController,
    PriceController,
    PriceRulesController,
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
    ExportExcelController,
    InvoicePayController,
    StockController,
    StockDetailController,
    ReplaceStockController

};
use App\Models\Cart;
use App\Models\JobApi;
use App\Models\ReplaceStock;
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
        Route::get('/laporan/harian_index', [LaporanController::class, 'harian_index'])->name('laporan.harian_index');
        
        Route::get('/laporan/detail_day', [LaporanController::class, 'detail_day'])->name('laporan.detail_day');
        Route::get('/laporan/detail_day_data/{tanggal}', [LaporanController::class, 'detail_day_data'])->name('laporan.detail_day_data');
        Route::get('/laporan/day_payment', [LaporanController::class, 'day_payment'])->name('laporan.day_payment');
        Route::get('/laporan/data_day_payment/{tanggal}', [LaporanController::class, 'data_day_payment'])->name('laporan.data_day_payment');
        Route::get('/laporan/day_report_payment/{tanggal}', [LaporanController::class, 'day_report_payment'])->name('laporan.day_report_payment');

        Route::get('/laporan/day_order', [LaporanController::class, 'day_order'])->name('laporan.day_order');
        Route::get('/laporan/data_day_order/{tanggal}', [LaporanController::class, 'data_day_order'])->name('laporan.data_day_order');
        Route::get('/laporan/day_report_order/{tanggal}', [LaporanController::class, 'day_report_order'])->name('laporan.day_report_order');
        
        Route::get('/laporan/day_cart', [LaporanController::class, 'day_cart'])->name('laporan.day_cart');
        Route::get('/laporan/detail_day_cart/{tanggal}', [LaporanController::class, 'detail_day_cart'])->name('laporan.detail_day_cart');
        Route::get('/laporan/day_cart_data/{tanggal}', [LaporanController::class, 'day_cart_data'])->name('laporan.day_cart_data');

        Route::get('/laporan/getDetailHarian', [LaporanController::class, 'getDetailHarian'])->name('laporan.getDetailHarian');

        Route::get('/laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
        Route::get('/laporan/getHarian', [LaporanController::class, 'getHarian'])->name('laporan.getHarian');
        Route::get('/laporan/data_harian/{tanggal}', [LaporanController::class, 'data_harian'])->name('laporan.data_harian');
        
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');
        Route::get('/laporan/report/{tanggal}', [LaporanController::class, 'report'])->name('laporan.exportListTransaction');

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
        
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/data', [StockController::class, 'data'])->name('stock.data');
        Route::get('/stock/add_stock/{id}', [StockController::class, 'add_stock'])->name('stock.add_stock');
        Route::get('/stock/show_detail/{id}', [StockController::class, 'show_detail'])->name('stock.show_detail');
        Route::get('/stock/edit_stock/{id}', [StockController::class, 'edit_stock'])->name('stock.edit_stock');
        Route::put('/stock/edit_stock/{id}', [StockController::class, 'edit_stock'])->name('stock.edit_stock');
        Route::get('/stock/cancel/{id}', [StockController::class, 'cancel'])->name('stock.cancel');
        Route::get('/stock/status/{id}', [StockController::class, 'status'])->name('stock.status');
        Route::post('/stock/store_stock', [StockController::class, 'store_stock'])->name('stock.store_stock');
        Route::resource('/stock', StockController::class)
        ->except('create', 'show', 'edit');    
        
        Route::get('/stock_detail/{id}', [StockDetailController::class, 'index'])->name('stock_detail.index');
        Route::get('/stock_detail/data/{id}', [StockDetailController::class, 'data'])->name('stock_detail.data');
        Route::delete('/stock_detail/{id}', [StockDetailController::class, 'destroy'])->name('stock_detail.destroy');
        Route::post('/stock_detail/addProduct/{sku}/{count}/{id_stock}', [StockDetailController::class, 'addProduct'])->name('stock_detail.addProduct');
 
    });
        
        Route::get('/export_excel', [ExportExcelController::class, 'index'])->name('export_excel.index');
        Route::get('/export_excel/excel', [ExportExcelController::class, 'excel'])->name('export_excel.excel');
        Route::get('/export_excel/export', [ExportExcelController::class, 'export'])->name('export_excel.export');
        Route::get('/export_excel/report', [ExportExcelController::class, 'report'])->name('export_excel.report');
        Route::post('/export_excel/import', [ExportExcelController::class, 'import'])->name('export_excel.import');

        Route::get('/export_excel/export_price', [ExportExcelController::class, 'export_price'])->name('export_excel.export_price');
        Route::post('/export_excel/import_price', [ExportExcelController::class, 'import_price'])->name('export_excel.import_price');
        
        Route::get('/export_excel/export_price_rules', [ExportExcelController::class, 'export_price_rules'])->name('export_excel.export_price_rules');
        Route::post('/export_excel/import_price_rules', [ExportExcelController::class, 'import_price_rules'])->name('export_excel.import_price_rules');

        Route::get('/file-import',[ReplaceStockController::class,'importView'])->name('import-view');
        Route::post('/import',[ReplaceStockController::class,'import'])->name('import');
        Route::get('/export-users',[ReplaceStockController::class,'exportUsers'])->name('export-users');
        Route::get('/replace-stock',[ReplaceStockController::class,'replaceStock'])->name('replace-stock');
        Route::get('/empty-table',[ReplaceStockController::class,'emptyTable'])->name('empty-table');


        //CART ROUTE FULL (SPV, KSR, PDM)
        Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('/invoice/add_invoice/{id}', [InvoiceController::class, 'add_invoice'])->name('invoice.add_invoice');
        Route::get('/invoice/data', [InvoiceController::class, 'data'])->name('invoice.data');
        Route::get('/invoice/cancel/{id}', [InvoiceController::class, 'cancel'])->name('invoice.cancel');
        Route::get('/invoice/show_detail/{id}', [InvoiceController::class, 'show_detail'])->name('invoice.show_detail');
        Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
        Route::get('/invoice/create/{customer_id}/{id_pembelian}/{total}', [InvoiceController::class, 'create'])->name('invoice.create');

        Route::get('/invoice/confirm/{id}/{date}', [InvoiceController::class, 'confirm'])->name('invoice.confirm');
        Route::get('/invoice/hapus/{id}', [InvoiceController::class, 'hapus'])->name('invoice.hapus');
        Route::get('/invoice/pay/{id}', [InvoiceController::class, 'pay'])->name('invoice.pay');

        Route::get('/invoice_pay/{id}', [InvoicePayController::class, 'index'])->name('invoice_pay.index');
        Route::get('/invoice_pay/data/{id}', [InvoicePayController::class, 'data'])->name('invoice_pay.data');
        Route::delete('/invoice_pay/{id}', [InvoicePayController::class, 'destroy'])->name('invoice_pay.destroy');
        Route::post('/invoice_pay/store', [InvoicePayController::class, 'store'])->name('invoice_pay.store');

        //job
          Route::get('/job_api', [JobApiController::class, 'index'])->name('job_api.index');
          Route::get('/job_api/data', [JobApiController::class, 'data'])->name('job_api.data');
          Route::get('/job_api/sync/{sku}', [JobApiController::class, 'sync'])->name('job_api.sync');
          Route::get('/job_api/replace', [JobApiController::class, 'replace'])->name('job_api.replace');
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
        
        Route::get('/cart/index_kasir', [CartController::class, 'index_kasir'])->name('cart.index_kasir');
        Route::get('/cart/index_staff', [CartController::class, 'index_staff'])->name('cart.index_staff');
        Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
        Route::get('/cart/data_kasir', [CartController::class, 'data_kasir'])->name('cart.data_kasir');
        Route::get('/cart/data_staff', [CartController::class, 'data_staff'])->name('cart.data_staff');
        Route::get('/cart/new', [CartController::class, 'new'])->name('cart.new');
        Route::get('/cart/produk', [CartController::class, 'produk'])->name('cart.produk');    
        Route::delete('/cart/purge_send/{id}', [CartController::class, 'purge_send'])->name('cart.purge_send');
        Route::delete('/cart/purge_cart/{id}', [CartController::class, 'purge_cart'])->name('cart.purge_cart');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::get('/cart/{id}/creates', [CartController::class, 'create'])->name('cart.create');
        Route::get('/cart/{id}/create_staff', [CartController::class, 'create_staff'])->name('cart.create_staff');
        Route::get('/cart/{id}/edit_cart', [CartController::class, 'edit_cart'])->name('cart.edit_cart');
        Route::get('/cart/show_send/{id}', [CartController::class, 'show_send'])->name('cart.show_send');
        Route::get('/cart/show_cart/{id}', [CartController::class, 'show_cart'])->name('cart.show_cart');
        Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
        Route::post('/cart/transaction_store', [CartController::class, 'transaction_store'])->name('cart.transaction_store');
        Route::post('/cart/store/{customer_id}', [CartController::class, 'store'])->name('cart.store');
        Route::post('/cart/cancel/{id_product}/{customer_id}', [CartController::class, 'cancel'])->name('cart.cancel');
        
        Route::post('/cart/addProduct/{id}/{count}/{customer_id}', [CartController::class, 'addProduct'])->name('cart.addProduct');
        Route::post('/cart/edit_addProduct/{id}/{count}/{nomor_nota}/{customer_id}', [CartController::class, 'edit_addProduct'])->name('cart.edit_addProduct');
        Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/update_transaction/{id}', [CartController::class, 'update_transaction'])->name('cart.update_transaction');
        Route::post('/cart/update_price/{id}', [CartController::class, 'update_price'])->name('cart.update_price');
        Route::get('/cart/checkout/{id}', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
        
        Route::get('/cart/sku/{sku}', [CartController::class, 'sku'])->name('cart.sku');
        Route::get('/cart/loadForm/{nomor_nota}', [CartController::class, 'loadForm'])->name('cart.loadForm');
        Route::get('/cart/transaction_detail', [CartController::class, 'transaction_detail'])->name('cart.transaction_detail');
        Route::post('/cart/transaction', [CartController::class, 'transaction'])->name('cart.transaction');
        Route::get('/cart/destroy_session', [CartController::class, 'destroy_session'])->name('cart.destroy_session');
        Route::get('/cart/transaction_data/{id}', [CartController::class, 'transaction_data'])->name('cart.transaction_data');
        Route::get('/cart/sess_name/{id}/{text}', [CartController::class, 'sess_name'])->name('cart.sess_name');
        Route::get('/cart/loadType/{id}/{text}', [CartController::class, 'loadType'])->name('cart.loadType');
        Route::get('/cart/{nomor_nota}/edit_data', [CartController::class, 'edit_data'])->name('cart.edit_data');
        Route::delete('cart/cart_transaction_destroy/{id}', [CartController::class, 'cart_transaction_destroy'])->name('cart.cart_transaction_destroy');

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
        Route::get('/produk/stock_in', [ProdukController::class, 'stock_in'])->name('produk.stock_in');
        Route::get('/produk/stock_out', [ProdukController::class, 'stock_out'])->name('produk.stock_out');
        Route::get('/produk/stock_replace', [ProdukController::class, 'stock_replace'])->name('produk.stock_replace');

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::get('/produk/data_cepat', [ProdukController::class, 'data_cepat'])->name('produk.data_cepat');
        // Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);
        
        // Route::resource('/price', PriceController::class);
        Route::get('/price/index', [PriceController::class, 'index'])->name('price.index');
        Route::get('/price/data', [PriceController::class, 'data'])->name('price.data');
        // Route::get('/price/{id}', [PriceController::class, 'show'])->name('price.show');
        // Route::post('/price/update/{id}', [PriceController::class, 'update'])->name('price.update');
        // Route::delete('/price/{id}', [PriceController::class, 'destroy'])->name('price.destroy');
        // Route::post('/price/store', [PriceController::class, 'store'])->name('price.store');
        Route::resource('/price', PriceController::class);


        //Price Rules
        Route::get('/price_rules/index', [PriceRulesController::class, 'index'])->name('price_rules.index');
        Route::get('/price_rules/data', [PriceRulesController::class, 'data'])->name('price_rules.data');
        // Route::get('/price_rules/{id}', [PriceRulesController::class, 'show'])->name('price_rules.show');
        // Route::post('/price_rules/update/{id}', [PriceRulesController::class, 'update'])->name('price_rules.update');
        // Route::delete('/price_rules/{id}', [PriceRulesController::class, 'destroy'])->name('price_rules.destroy');
        // Route::post('/price_rules/store', [PriceRulesController::class, 'store'])->name('price_rules.store');
        Route::resource('/price_rules', PriceRulesController::class);


    });

    Route::group(['middleware' => 'level:1,2,3'], function () {
        Route::get('/pembelian/only_transaksi', [PembelianController::class, 'only_transaksi'])->name('pembelian.only_transaksi');
        Route::get('/pembelian/order_online', [PembelianController::class, 'order_online'])->name('pembelian.order_online');

        
        Route::get('/pembelian/list-cart', [PembelianController::class, 'list_cart'])->name('pembelian.list_cart');
        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/data_online', [PembelianController::class, 'data_online'])->name('pembelian.data_online');
        Route::get('/pembelian/nota', [PembelianController::class, 'nota'])->name('pembelian.nota');
        Route::get('/pembelian/nota_besar/{id}', [PembelianController::class, 'nota_besar'])->name('pembelian.nota_besar');

        Route::get('/pembelian/each_nota/{id}', [PembelianController::class, 'each_nota'])->name('pembelian.each_nota');
        Route::get('/pembelian/refund/{id}', [PembelianController::class, 'refund'])->name('pembelian.refund');
        Route::get('/pembelian/bug', [PembelianController::class, 'bug'])->name('pembelian.bug');

        Route::get('/pembelian/selesai', [PembelianController::class, 'selesai'])->name('pembelian.selesai');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::get('/pembelian/{id}/create_online', [PembelianController::class, 'create_online'])->name('pembelian.create_online');

        Route::resource('/pembelian', PembelianController::class)
            ->except('create');     
        Route::get('/pembelian/nota-kecil', [PembelianController::class, 'notaKecil'])->name('pembelian.nota_kecil');
        // Route::get('/pembelian/member', [PembelianController::class, 'member'])->name('pembelian.member');

        Route::get('/pembelian_detail/nota-kecil', [PembelianDetailController::class, 'notaKecil'])->name('pembelian_detail.notaKecil');
        Route::get('/pembelian_detail/index_online', [PembelianDetailController::class, 'index_online'])->name('pembelian_detail.index_online');

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/{id}/data_online', [PembelianDetailController::class, 'data_online'])->name('pembelian_detail.data_online');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::post('/pembelian_detail/addProduct/{sku}/{count}/{customer_id}', [PembelianDetailController::class, 'addProduct'])->name('pembelian_detail.addProduct');
        Route::post('/pembelian_detail/addProductKasir/{id}/{count}/{harga}/{customer_id}/{nomor_nota}', [PembelianDetailController::class, 'addProductKasir'])->name('pembelian_detail.addProductKasir');
        Route::post('/pembelian_detail/addProductSlider/{id}/{count}/{customer_id}', [PembelianDetailController::class, 'addProductSlider'])->name('pembelian_detail.addProductSlider');
        Route::post('/pembelian_detail/cancel/{id_product}/{customer_id}', [PembelianDetailController::class, 'cancel'])->name('PembelianDetailController.cancel');
        Route::get('/pembelian_detail/address/{id}', [PembelianDetailController::class, 'address'])->name('pembelian_detail.address');
        Route::post('/pembelian_detail/counter/{id}', [PembelianDetailController::class, 'counter'])->name('pembelian_detail.counter');
        Route::post('/pembelian_detail/case/{id}/{case}', [PembelianDetailController::class, 'case'])->name('pembelian_detail.case');
        Route::post('/pembelian_detail/discount/{id}/{transaction}/{customer}', [PembelianDetailController::class, 'discount'])->name('pembelian_detail.discount');

        Route::get('/pembelian_detail/kembalian/{kembalian}/{total}', [PembelianDetailController::class, 'kembalian'])->name('pembelian_detail.kembalian');
        Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');    
    });

    Route::group(['middleware' => 'level:1,3'], function () {
       //transaksi full
    });

    Route::group(['middleware' => 'level:1,2,3'], function () {
       //view tranksaksi
    });

Route::group(['middleware' => 'level:1,2'], function () {
       //full akses
    });
