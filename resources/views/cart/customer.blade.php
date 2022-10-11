<div class="modal fade" id="modal-customer" tabindex="-1" role="dialog" aria-labelledby="modal-customer">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Customer</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-customer">
                <thead>
                        <th>Nama Customer</th>
                        <th>Nomor Telepon</th>
                        {{-- <th>Tipe Customer</th> --}}
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($member as $key => $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->phone }}</td>
                                {{-- <td>{{ $item->type->role }}</td> --}}
                                <td>
                                    <a href="{{ route('cart.create', $item->id) }}" class="btn btn-primary btn-xs btn-flat">
                                        <i class="fa fa-check-circle"> pilih</i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>