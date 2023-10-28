<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Produk</h4>
            </div>
            <div class="modal-body">
                
                <table class="table table-striped table-bordered table-produk">
                    <thead>
                        <th>SKU</th>
                        <th>title</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        <section id="loading">
                            <div id="loading-content">
                                
                            @foreach ($produk as $key => $item)
                                <tr>
                                    <td>{{ $item->sku }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ format_uang($item->base_price) }}</td>
                                    <td>{{ $item->stock }}</td>

                                    {{-- <td>{{ format_uang($item->reseller_price) }}</td> --}}

                                    <td>
                                    {{-- <a href="#" class="btn btn-success btn-xs "
                                        onclick="pilihProduk('{{ $item->id }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a> --}}
                                    <label class="switch">
                                        <input onclick="multi({{ $item->id }}, this);" id="{{ $item->id }}"   type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
    
                                    {{-- <a href="#" class="btn btn-success btn-xs btn-flat"
                                        onclick="cekStok('{{ route('produk.sku', $item->sku) }}')">
                                        <i class="fa fa-cubes"></i>
                                        Pilih
                                    </a> --}}
                                    </td>
                                </tr>
                            @endforeach

                            </div>
                        </section>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>