<div class="modal fade" id="modal-invoice" tabindex="-1" role="dialog" aria-labelledby="modal-invoice">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismis="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Pembayaran Invoice</h4>
                </div>
                
                <div class="modal-body">
                    <div class="hidden form-group row">
                        {{-- <label for="invoice_id" class="col-lg-2 col-lg-offset-1 control-label">invoice_id</label> --}}
                        <div class="col-lg-6">
                            <input type="hidden" name="invoice_id" id="invoice_id" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-lg-2 col-lg-offset-1 control-label">Nama Pembayar</label>
                        <div class="col-lg-6">
                            <input type="text" name="name" id="name" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="number_invoice" class="col-lg-2 col-lg-offset-1 control-label">number_invoice</label>
                        <div class="col-lg-6">
                            <input type="text" name="number_invoice" id="number_invoice" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="number_ref" class="col-lg-2 col-lg-offset-1 control-label">number_ref</label>
                        <div class="col-lg-6">
                            <input type="text" name="number_ref" id="number_ref" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
                    {{-- <div class="form-group row">
                        <label for="invoice_amount" class="col-lg-2 col-lg-offset-1 control-label">invoice_amount</label>
                        <div class="col-lg-6">
                            <input type="text" name="invoice_amount" id="invoice_amount" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="payment_method" class="col-lg-2 col-lg-offset-1 control-label">Metode Pembayaran</label>
                        
                        <div class="col-lg-6">
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="Tunai">Tunai</option>
                                <option value="BCA an Asari">BCA an Asari</option>
                                <option value="BCA an PT">BCA an PT</option>
                                <option value="BRI an PT">BRI an PT</option>
                                <option value="Mandiri an PT">Mandiri an PT</option>
                                <option value="QRIS BRI">QRIS BRI</option>
                                <option value="QRIS Mandiri">QRIS Mandiri</option>
                                <option value="EDC BRI">EDC BRI</option>
                                <option value="EDC Mandiri">EDC Mandiri</option>
                                <option value="EDC BCA">EDC BCA</option>
                                {{-- <option value="Shopee">Shopee</option>
                                <option value="COD">COD</option> --}}
                                {{-- <option onclick="addInvoice()" value="Invoice">Invoice</option> --}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="amount" class="col-lg-2 col-lg-offset-1 control-label">Jumlah Terbayar</label>
                        <div class="col-lg-6">
                            <input type="text" name="amount" id="amount" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="payment_date" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Dibayar</label>
                        <div class="col-lg-6">
                            <input type="date" name="payment_date" id="payment_date" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="note" class="col-lg-2 col-lg-offset-1 control-label">Catatan</label>
                        <div class="col-lg-6">
                            <input type="text" name="note" id="note" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="due_date" class="col-lg-2 col-lg-offset-1 control-label">due_date</label>
                        <div class="col-lg-6">
                            <input type="date" name="due_date" id="due_date" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div> --}}
                </div>
                
                
                <div class="modal-footer">
                    {{-- <a href="#" class="btn btn-success btn-xs btn-flat"
                                        onclick="cekStok('{{ route('produk.sku', $item->sku) }}')">
                                        <i class="fa fa-cubes"></i>
                                        Simpan
                    </a> --}}
                    {{-- <button onclick="confirm()" type="button" class="btn-sm btn-flat btn-success" ><i class="fa fa-save"></i> Simpan</button> --}}
                    <button onclick="cancel()" type="button" class=" btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>


        </form>
    </div>
</div>