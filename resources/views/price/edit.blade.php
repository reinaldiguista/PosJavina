<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit">
    <div class="modal-dialog modal-sm" role="document">
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
                        <label for="sku" class="col-lg-3 col-lg-offset-1 control-label">SKU Produk</label>
                        <div class="col-lg-6">
                            <input type="text" name="sku_produk" id="sku_produk" class="form-control" val="sku_produk" autofocus readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                   
                    <div class="form-group row">
                        <label for="harga_terendah" class="col-lg-3 col-lg-offset-1 control-label">Harga 1</label>
                        <div class="col-lg-6">
                            <input type="number" name="harga_1" id="harga_1" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_terendah" class="col-lg-3 col-lg-offset-1 control-label">Harga 2</label>
                        <div class="col-lg-6">
                            <input type="number" name="harga_2" id="harga_2" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_terendah" class="col-lg-3 col-lg-offset-1 control-label">Harga 3</label>
                        <div class="col-lg-6">
                            <input type="number" name="harga_3" id="harga_3" class="form-control" required>
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