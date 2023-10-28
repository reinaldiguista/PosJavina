## Tentang Aplikasi

Aplikasi POS atau point of sales ini adalah aplikasi yang digunakan untuk mengelola penjualan offline tanaman anggrek pada Taman Arjuno, Kabupaten Malang oleh kasir. Aplikasi ini dibuat menggunakan Laravel v8.* dan minimal PHP v7

### Beberapa Fitur yang tersedia:

- Manajemen Produk (CRUD)
  - Export dan Import data produk
- Manajemen Data Customer (CRUD)
- Manajemen Data Cart/Keranjang (CRUD)
- Manajemen Data harga tiap produk
  - Export dan Import data harga
- Manajemen Data aturan rentang harga tiap produk
  - Export dan Import data rentang harga
- Transaksi Penjualan
  - Cetak nota thermal printer 58 mm
  - Cetak Nota PDF
- Report Penjualan harian
  - Detail keranjang yang dibuat
  - Detail tiap produk per transaksi
  - Detail penjualan berdasarkan nomor nota
  - Detail penjualan berdasarkan metode pembayaran
- Manajemen User/Employee


### Setup Aplikasi
Jalankan perintah 
```bash
composer update
```
atau:
```bash
composer install
```
Copy file .env dari .env.example
```bash
cp .env.example .env
```
Konfigurasi file .env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=example_app
DB_USERNAME=root
DB_PASSWORD=1234
```
Opsional
```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:QGRW4K7UVzS2M5HE2ZCLlUuiCtOIzRSfb38iWApkphE=
APP_DEBUG=true
APP_URL=http://example-app.test
```
Generate key
```bash
php artisan key:generate
```

Menjalankan aplikasi
```bash
php artisan serve
```
Catatan : Terdapat maatwebsite/excel, yajra untuk datatable, serta menggunakan template dashboard AdminLTE
## License

[MIT license](https://opensource.org/licenses/MIT)
