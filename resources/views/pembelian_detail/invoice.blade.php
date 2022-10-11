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
                    <h4 class="modal-title"></h4>
                </div>
                
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="customer" class="col-lg-2 col-lg-offset-1 control-label">Customer</label>
                        <div class="col-lg-6">
                            <input type="text" name="customer" id="customer" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
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
                    </div>
                    <div class="form-group row">
                        <label for="invoice_amount" class="col-lg-2 col-lg-offset-1 control-label">invoice_amount</label>
                        <div class="col-lg-6">
                            <input type="text" name="invoice_amount" id="invoice_amount" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="invoice_debt" class="col-lg-2 col-lg-offset-1 control-label">invoice_debt</label>
                        <div class="col-lg-6">
                            <input type="text" name="invoice_debt" id="invoice_debt" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="created_at" class="col-lg-2 col-lg-offset-1 control-label">created_at</label>
                        <div class="col-lg-6">
                            <input type="date" name="created_at" id="created_at" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="due_date" class="col-lg-2 col-lg-offset-1 control-label">due_date</label>
                        <div class="col-lg-6">
                            <input type="date" name="due_date" id="due_date" rows="3" class="form-control" required>
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