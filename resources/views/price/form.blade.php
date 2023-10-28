<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-sm" role="document">
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
                        <label for="sku_produk" class="col-lg-2 col-lg-offset-1 control-label">SKU Produk</label>
                        <div class="col-lg-6">
                            <select name="sku_produk" id="sku_produk" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($sku as $item)
                                    <option value="{{ $item->sku }}">{{ $item->sku }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label for="sku" class="col-lg-2 col-lg-offset-1 control-label">sku</label>
                        <div class="col-lg-6">
                            <input type="text" name="sku_produk" id="sku_produk" class="form-control" val="sku_produk" autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
                   
                    <div class="form-group row">
                        <label for="Harga_1" class="col-lg-2 col-lg-offset-1 control-label">Harga 1</label>
                        <div class="col-lg-6">
                            <input type="number" name="Harga_1" id="Harga_1" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price" class="col-lg-2 col-lg-offset-1 control-label">Harga 2</label>
                        <div class="col-lg-6">
                            <input type="number" name="Harga_2" id="Harga_2" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price" class="col-lg-2 col-lg-offset-1 control-label">Harga 3</label>
                        <div class="col-lg-6">
                            <input type="number" name="Harga_3" id="Harga_3" class="form-control" required>
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