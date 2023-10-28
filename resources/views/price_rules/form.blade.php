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
                        <label for="sku_produk" class="col-lg-2 col-lg-offset-1 control-label">Kategori Produk</label>
                        <div class="col-lg-6">
                            <select name="kategori_produk" id="kategori_produk" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                <option value="{{ $item->kategori }}">{{ $item->kategori }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
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

                    <div class="form-group row">
                        <label for="limit_1" class="col-lg-2 col-lg-offset-1 control-label">Batas bawah harga 2</label>
                        <div class="col-lg-6">
                            <input type="number" name="limit_1" id="limit_1" class="form-control" required >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="limit_2" class="col-lg-2 col-lg-offset-1 control-label">batas bawah harga 3</label>
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