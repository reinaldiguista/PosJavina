<div class="modal fade" id="modal-form_day_payment" tabindex="-1" role="dialog" aria-labelledby="modal-form_day_payment">
    <div class="modal-dialog modal-sm" role="document">
        <form action="{{ route('laporan.day_payment') }}" method="get" data-toggle="validator" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detail Laporan</h4>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group row">
                        <label for="tanggal_akhir" style="margin-right: 5px" class="col-lg-2 col-lg-offset-1 control-label">Tanggal</label>
                        <div class="col-lg-6">
                            <input type="text" name="tanggal_akhir" id="tanggal_akhir" class="form-control datepicker" required
                                value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}"
                                style="border-radius: 0 !important;">
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