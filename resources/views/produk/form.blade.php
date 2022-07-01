<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
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
                        <label for="title" class="col-lg-2 col-lg-offset-1 control-label">Nama</label>
                        <div class="col-lg-6">
                            <input type="text" name="title" id="title" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="id_kategori" class="col-lg-2 col-lg-offset-1 control-label">Kategori</label>
                        <div class="col-lg-6">
                            <select name="id_kategori" id="id_kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="sku" class="col-lg-2 col-lg-offset-1 control-label">sku</label>
                        <div class="col-lg-6">
                            <input type="text" name="sku" id="sku" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-lg-2 col-lg-offset-1 control-label">description</label>
                        <div class="col-lg-6">
                            <textarea type="text" name="description" id="description" class="form-control"></textarea>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="volmetric" class="col-lg-2 col-lg-offset-1 control-label">volmetric</label>
                        <div class="col-lg-6">
                            <input type="float" name="volmetric" id="volmetric" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price" class="col-lg-2 col-lg-offset-1 control-label">price</label>
                        <div class="col-lg-6">
                            <input type="number" name="price" id="price" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="agen_price" class="col-lg-2 col-lg-offset-1 control-label">agen_price</label>
                        <div class="col-lg-6">
                            <input type="number" name="agen_price" id="agen_price" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="reseller_price" class="col-lg-2 col-lg-offset-1 control-label">reseller_price</label>
                        <div class="col-lg-6">
                            <input type="number" name="reseller_price" id="reseller_price" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="offline_price" class="col-lg-2 col-lg-offset-1 control-label">offline_price</label>
                        <div class="col-lg-6">
                            <input type="number" name="offline_price" id="offline_price" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="handling_fee" class="col-lg-2 col-lg-offset-1 control-label">handling_fee</label>
                        <div class="col-lg-6">
                            <input type="number" name="handling_fee" id="handling_fee" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="stock" class="col-lg-2 col-lg-offset-1 control-label">stock</label>
                        <div class="col-lg-6">
                            <input type="number" name="stock" id="stock" class="form-control" value="0">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="stock_offline" class="col-lg-2 col-lg-offset-1 control-label">stock_offline</label>
                        <div class="col-lg-6">
                            <input type="number" name="stock_offline" id="stock_offline" class="form-control" required value="0">
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