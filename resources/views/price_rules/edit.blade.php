<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit">
    <div class="modal-dialog modal-m" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Produksss</h4>
                </div>
                <div class="modal-body">
                    
                    {{-- <div class="form-group row">
                        <label for="title" class="col-lg-2 col-lg-offset-1 control-label">Nama</label>
                        <div class="col-lg-6">
                            <input type="text" name="title" id="title" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
                    {{-- <div class="form-group row">
                        <label for="id_kategori" class="col-lg-3 col-lg-offset-1 control-label">SKU : </label>
                        <div class="col-lg-2">
                            <select name="sku_produk" id="sku_produk" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($sku as $item)
                                <option value="{{ $item->sku }}">{{ $item->sku }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
            
                    {{-- <div class="form-group row">
                        <label for="kode_kebun" class="col-lg-3 col-lg-offset-1 control-label">Kode Kebun : </label>
                        <div class="col-lg-6">
                            <input type="text" name="kode_kebun" id="kode_kebun" class="form-control" val="kode_kebun" autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="sku" class="col-lg-3 col-lg-offset-1 control-label">Kategori Produk</label>
                        <div class="col-lg-6">
                            <input type="text" name="kategori_produk" id="kategori_produk" class="form-control" val="sku_produk" autofocus readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                   
                    <div class="form-group row">
                        <label for="harga_terendah" class="col-lg-3 col-lg-offset-1 control-label">Limit 1</label>
                        <div class="col-lg-6">
                            <input type="number" name="limit_1" id="limit_1" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_terendah" class="col-lg-3 col-lg-offset-1 control-label">Limit 2</label>
                        <div class="col-lg-6">
                            <input type="number" name="limit_2" id="limit_2" class="form-control" required>
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