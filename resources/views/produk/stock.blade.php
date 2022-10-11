<div class="modal fade" id="modal-stock" tabindex="-1" role="dialog" aria-labelledby="modal-stock">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Info Stonk website IsiTaman</h4>
            </div>

            <div class="modal-body">
                <div class="form-group row">
                    <label style="float: left" for="nominal" class="col-lg-2 control-label">SKU</label>
                    <div id="sku" style="float: right" class="col-lg-8">
                        <input style="align-content: flex-end" type="text" id="sku_produk" name="sku_produk" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label style="float: left" for="nominal" class="col-lg-2 control-label">Jumlah Stok</label>
                    <div id="stock" style="float: right" class="col-lg-8">
                        <input style="align-content: flex-end" type="text" id="stock_produk" name="stock_produk" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <p style="text-align: center">
                        Masukkan jumlah produk yang diinginkan :
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
            <button type="button" class="btn btn-info" data-dismiss="modal">Kembali</button>
                        <a href="#" class="btn btn-success btn-flat"
                            onclick="kirimProduk()">
                            <i class="fa fa-arrow-up"></i>
                            Kirim
                        </a>
                        <a href="#" class="btn btn-danger btn-flat"
                            onclick="KurangiProduk()">
                            <i class="fa fa-arrow-down"></i>
                            Kurangi
                        </a>  
            </div>
        </div>
    </div>
</div>