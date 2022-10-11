<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
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
                        <label for="name" class="col-lg-2 col-lg-offset-1 control-label">name</label>
                        <div class="col-lg-6">
                            <input type="text" name="name" id="name" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="discount" class="col-lg-2 col-lg-offset-1 control-label">Besar discount</label>
                        <div class="col-lg-6">
                            <input type="text" name="discount" id="discount" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="discount_limit" class="col-lg-2 col-lg-offset-1 control-label">discount_limit</label>
                        <div class="col-lg-6">
                            <input type="text" name="discount_limit" id="discount_limit" rows="3" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="count_limit" class="col-lg-2 col-lg-offset-1 control-label">count_limit</label>
                        <div class="col-lg-6">
                            <input type="text" name="count_limit" id="count_limit" rows="3" class="form-control" required>
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