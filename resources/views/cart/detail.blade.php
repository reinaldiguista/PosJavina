{{-- {{ $customer_id }} --}}
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Penjualan</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-detail">
                    <thead>
                        <th width="5%">No</th>
                        <th>Product Id</th>
                        <th>Base Price</th>
                        <th>Count</th>
                        <th>Final Price</th>
                        <th>isSpecialCase</th>
                        <th>aksi</th>
                    </thead>
                </table>
            </div>
            {{-- @foreach ($detail as $detail) --}}
            <div class="modal-footer">  
                <a href="{{ route('transaksi.index') }}">
                <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Checkout</button>
                </a>
            </div>
        </div>
    </div>
</div>