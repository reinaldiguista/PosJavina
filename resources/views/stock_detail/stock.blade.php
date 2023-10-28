<div class="modal fade" id="modal-stock" tabindex="-1" role="dialog" aria-labelledby="modal-stock">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Info Stok website IsiTaman</h4>
            </div>

            <div class="modal-body">
                <div class="form-group row">
                    <label style="float: left" for="nominal" class="col-lg-2 control-label">SKU</label>
                    <div id="sku" style="float: right" class="col-lg-8">
                        <input style="align-content: flex-end" type="text" id="sku_produk" name="sku_produk" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label style="float: left" for="nominal" class="col-lg-4 control-label">Stok Saat Ini</label>
                    <div id="stock" style="float: right" class="col-lg-8">
                        <input style="align-content: flex-end" type="text" id="stock_produk" name="stock_produk" class="form-control" readonly>
                        <br>
                        <button name="sukses" id="sukses" style="display: none" class="btn btn-success btn-sm fa fa-check-circle-o"> isitaman</button>
                        <button name="gagal" id="gagal" style="display: none" class="btn btn-danger btn-sm fa fa-times-circle-o"> local</button>
                    </div>
                </div>
                <div class="form-group row">
                    <p style="text-align: center">
                        Masukkan jumlah produk :
                    </p>
                    {{-- <label  for="nominal" class="col-lg control-label">Masukkan Jumlah Produk yang ingin dibeli</label>            --}}
                </div>
                
                <div class="form-group row">
                    <label style="float: left" for="nominal" class="col-lg-2 control-label">Jumlah</label>
                    <div id="jumlah_beli" style="float: right" class="col-lg-8">
                        <input style="align-content: flex-end" type="text" id="produk_kembali" name="produk_kembali" class="form-control">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                {{-- @if ($produk as $key => $item)
                    
                @endif
                @foreach ($produk as $key => $item)
                                  
            @endforeach --}}
            {{-- <a style="margin-bottom: 5px" href="#" class="btn btn-info btn-block btn-flat"
                            onclick="kirimProduk()">
                            <i class="fa fa-arrow-up"></i>
                            Tambah Stok
                        </a>  --}}
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <a href="#" class="btn btn-success "
                            onclick="pilihProduk()">
                            {{-- <i class="fa fa-arrow-down"></i> --}}
                            Confirm
                        </a>  
                        
            </div>
        </div>
    </div>
</div>