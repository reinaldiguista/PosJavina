<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="tipe" class="col-lg-2 col-lg-offset-1 control-label">Tipe Stok</label>
                        
                        <div class="col-lg-6">
                            <select name="tipe" id="tipe" class="form-control">
                                <option value="pilih">--pilih--</option>
                                <option value="Stok Masuk">Stok Masuk</option>
                                <option value="Stok Keluar">Stok Keluar</option>
                                {{-- <option onclick="tipe_lainnya()" value="Lainnya">Lainnya</option> --}}
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tanggal" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Barang</label>
                        <div class="col-lg-6">
                            <input type="date" name="tanggal" id="tanggal" class="form-control" val="sku" autofocus>
                            {{-- <span class="help-block with-errors"></span> --}}
                        </div>
                    </div>
                    
                    <div class="form-group row" id="stok_in" name="stok_in" style="display: block">
                        <label for="name" class="col-lg-2 col-lg-offset-1 control-label">Keterangan Stok Masuk</label>
                        <div class="col-lg-6">
                            <select name="name_in" id="name_in" class="form-control">
                                <option value="Petani Plasma">Petani Plasma</option>
                                <option value="Pindah Kebun">Pindah Kebun</option>
                                <option value="Kembali dari Perawatan">Kembali dari Perawatan</option>
                                <option value="Split dan Repoting">Split dan Repoting</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" id="stok_out" name="stok_out" style="display: block">
                        <label for="name" class="col-lg-2 col-lg-offset-1 control-label">Keterangan Stok Keluar</label>
                        <div class="col-lg-6">
                            <select name="name_out" id="name_out" class="form-control">
                                <option value="Masuk Perawatan">Masuk Perawatan</option>
                                <option value="Mati">Mati</option>
                                <option value="Split & Special Case">Split & Special Case</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" id="lainnya" name="lainnya" style="display: block">
                        <div id="lainnya_title" >
                            <label for="lainnya" class="col-lg-2 col-lg-offset-1 control-label">Lainnya</label>
                        </div>
                        <div id="lainnya_value"  class="col-lg-6">
                            <input type="text" id="lainnya" name="lainnya" class="form-control" placeholder="masukkan keterangan" >
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="id_kategori" class="col-lg-2 col-lg-offset-1 control-label">Kategori</label>
                        <div class="col-lg-6">
                            <select name="id_kategori" id="id_kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
                   
                    {{-- <div class="form-group row">
                        <label for="total_barang" class="col-lg-2 col-lg-offset-1 control-label">Total Barang</label>
                        <div class="col-lg-6">
                            <input type="number" name="total_barang" id="total_barang" class="form-control" >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label for="pengirim" class="col-lg-2 col-lg-offset-1 control-label">Pengirim</label>
                        <div class="col-lg-6">
                            <input type="text" name="pengirim" id="pengirim" class="form-control" >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="asal" class="col-lg-2 col-lg-offset-1 control-label">Asal</label>
                        <div class="col-lg-6">
                            <input type="text" name="asal" id="asal" class="form-control" >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="penerima" class="col-lg-2 col-lg-offset-1 control-label">Penerima</label>
                        <div class="col-lg-6">
                            <input type="text" name="penerima" id="penerima" class="form-control" >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lokasi_penerimaan" class="col-lg-2 col-lg-offset-1 control-label">Lokasi Penerimaan</label>
                        <div class="col-lg-6">
                            <input type="text" name="lokasi_penerimaan" id="lokasi_penerimaan" class="form-control" >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="catatan" class="col-lg-2 col-lg-offset-1 control-label">Catatan</label>
                        <div class="col-lg-6">
                            <input type="text" name="catatan" id="catatan" class="form-control" >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label for="catatan" class="col-lg-2 col-lg-offset-1 control-label">Status Penerimaan</label>
                        
                        <div class="col-lg-6">
                            <select name="tipe" id="tipe" class="form-control">
                                <option value=1>Diterima</option>
                                <option value=2>Pending</option>
                                <option value=3>Cancel</option>
                            </select>
                        </div>
                    </div> --}}
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>


