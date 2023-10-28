<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-m" role="document">
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
                        <label for="title" class="col-lg-3 col-lg-offset-1 control-label">Nama Produk : </label>
                        <div class="col-lg-6">
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sku" class="col-lg-3 col-lg-offset-1 control-label">Sku : </label>
                        <div class="col-lg-6">
                            <input type="text" name="sku" id="sku" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="title" class="col-lg-2 col-lg-offset-1 control-label">Nama</label>
                        <div class="col-lg-6">
                            <input type="text" name="title" id="title" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Kategori : </label>
                        <div class="col-lg-2">
                            <select name="kategori" id="kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                <option value="{{ $item->kategori }}">{{ $item->kategori }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Jenis Produk : </label>
                        <div class="col-lg-6">
                            <select name="jenis_produk" id="jenis_produk" class="form-control" required>
                                <option value="">Pilih Jenis Produk</option>
                                @foreach ($jenis_produk as $item)
                                <option value="{{ $item->jenis_produk }}">{{ $item->jenis_produk }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Kondisi : </label>
                        <div class="col-lg-6">
                            <select name="kondisi" id="kondisi" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach ($kondisi as $item)
                                <option value="{{ $item->kondisi }}">{{ $item->kondisi }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Genus : </label>
                        <div class="col-lg-6">
                            <select name="genus" id="genus" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach ($genus as $item)
                                <option value="{{ $item->genus }}">{{ $item->genus }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Supplier : </label>
                        <div class="col-lg-6">
                            <select name="supplier" id="supplier" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach ($supplier as $item)
                                <option value="{{ $item->supplier }}">{{ $item->supplier }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Jenis Supplier : </label>
                        <div class="col-lg-6">
                            <select name="jenis_supplier" id="jenis_supplier" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach ($jenis_supplier as $item)
                                <option value="{{ $item->jenis_supplier }}">{{ $item->jenis_supplier }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Registrasi : </label>
                        <div class="col-lg-6">
                            <select name="registrasi_anggrek" id="registrasi_anggrek" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach ($registrasi_anggrek as $item)
                                <option value="{{ $item->registrasi_anggrek }}">{{ $item->registrasi_anggrek }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Grade : </label>
                        <div class="col-lg-6">
                            <select name="grade" id="grade" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach ($grade as $item)
                                <option value="{{ $item->grade }}">{{ $item->grade }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Hybrid / Spesies : </label>
                        <div class="col-lg-6">
                            <select name="hb_sp" id="hb_sp" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach ($hb_sp as $item)
                                <option value="{{ $item->hb_sp }}">{{ $item->hb_sp }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Kelompok Pasar : </label>
                        <div class="col-lg-6">
                            <select name="kelompok_pasar" id="kelompok_pasar" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach ($kelompok_pasar as $item)
                                <option value="{{ $item->kelompok_pasar }}">{{ $item->kelompok_pasar }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kode_kebun" class="col-lg-3 col-lg-offset-1 control-label">Kode Kebun : </label>
                        <div class="col-lg-6">
                            <input type="text" name="kode_kebun" id="kode_kebun" class="form-control" val="kode_kebun" autofocus>
                            {{-- <span class="help-block with-errors"></span> --}}
                        </div>
                    </div>
                   
                    <div class="form-group row">
                        <label for="harga_terendah" class="col-lg-3 col-lg-offset-1 control-label">Harga Terendah : </label>
                        <div class="col-lg-6">
                            <input type="number" name="harga_terendah" id="harga_terendah" class="form-control" >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="stock" class="col-lg-3 col-lg-offset-1 control-label">Stock : </label>
                        <div class="col-lg-6">
                            <input type="number" name="stock" id="stock" class="form-control" value="0">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="hpp" class="col-lg-3 col-lg-offset-1 control-label">HPP : </label>
                        <div class="col-lg-6">
                            <input type="number" name="hpp" id="hpp" class="form-control" value="0">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">Status Produk : </label>
                        <div class="col-lg-6">
                            <select name="enable" id="enable" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach ($enable as $item)
                                    @if ($item->enable == 0)        
                                        <option value="{{ $item->enable }}">Disable</option>
                                    @else
                                        <option value="{{ $item->enable }}">Enable</option>   
                                    @endif
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>