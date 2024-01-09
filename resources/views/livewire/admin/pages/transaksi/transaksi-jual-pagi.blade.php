<div>
    <x-slot name="header">
        <livewire:admin.global.page-header judul="Transaksi" subjudul="Jual Pagi" :breadcrumb="['Transaksi Jual Pagi']" />
    </x-slot>
    <div class="card">
        <div class="card-header">
            @if ($isEdit)
                <a href="#" wire:click='cancel' class="btn btn-warning mt-md-0 mt-2 ml-md-8">Cancel</a>
            @else
                <a href="#" wire:click='add' class="btn btn-primary mt-md-0 mt-2">Tambah Jual Pagi</a>
            @endif

        </div>
        <div class="card-body">
            @if ($isEdit)
                <div class="row">
                    <div class="col-md-2 pr-0">
                        <div class="form-group">
                            <label>Pilih Product:</label><br>
                            <button class="btn btn-primary btn-sm" wire:click="pilihProduct"
                                type="button">Pilih</button>
                        </div>
                    </div>
                    <div class="col-md-3 pl-0 pr-0">
                        <div class="form-group">
                            <label>Nama Product:</label>
                            <input type="text"
                                class="form-control {{ $errors->has('product_id') ? ' border-danger' : null }}"
                                value="{{ $product->name ?? '' }}" readonly>
                            @error('product_id')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1 pl-0 pr-0">
                        <div class="form-group">
                            <label>Beli:</label>
                            <input type="text"
                                class="form-control {{ $errors->has('product_id') ? ' border-danger' : null }}"
                                value="{{ $product->harga_beli ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-1 pl-0 pr-0">
                        <div class="form-group">
                            <label>Satuan:</label>
                            <input type="text"
                                class="form-control {{ $errors->has('product_id') ? ' border-danger' : null }}"
                                wire:model="satuan" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 pl-0 pr-0">
                        <div class="form-group">
                            <label>Jual:</label>
                            <input type="number"
                                class="form-control {{ $errors->has('harga_jual') ? ' border-danger' : null }}"
                                wire:model="harga_jual" readonly>
                            @error('harga_jual')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1 pl-0 pr-0">
                        <div class="form-group">
                            <label>QTY:</label>
                            <input type="number"
                                class="form-control {{ $errors->has('qty') ? ' border-danger' : null }}"
                                wire:model="qty" min="1" max="{{ $stock + 1 }}">
                            @error('qty')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 pl-0">
                        <div class="form-group">
                            <label>Sub Total:</label>
                            <input type="number"
                                class="form-control {{ $errors->has('sub_total') ? ' border-danger' : null }}"
                                wire:model="sub_total" readonly>
                            @error('sub_total')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" wire:click='tambahProduct'
                            class="btn btn-info btn-sm form-control">Tambah</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-table table-responsive shadow-0 mb-0 mt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Beli</th>
                                        <th>Jual</th>
                                        <th>Qty</th>
                                        <th>Sub Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_add_product as $a)
                                        <tr>
                                            <td>{{ $a['id'] }}</td>
                                            <td>{{ $a['name'] }}</td>
                                            <td>{{ $a['harga_beli'] }}</td>
                                            <td>{{ $a['harga_jual'] }}</td>
                                            <td>{{ $a['qty'] }}</td>
                                            <td>{{ $a['sub_total'] }}</td>
                                            <td><button type="button"
                                                    class="btn bg-pink-400 btn-icon rounded-round btn-sm"
                                                    wire:click="hapusProduct({{ $a['id'] }},{{ $a['sub_total'] }})"><i
                                                        class="icon-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                        @php
                                            $jumlah += $a['qty'];
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="4"></td>
                                        <td><b>{{ $jumlah }}</b></td>
                                        <td><b>{{ $total }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @error('list_add_product')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label class="col-form-label col-lg-2">Agent</label>
                    <div class="col-lg-10">
                        <div class="input-group">
                            <input type="text"
                                class="form-control {{ $errors->has('agent_id') ? ' border-danger' : null }}"
                                placeholder="Pilih Agent" value="{{ $agent->name ?? '' }}">
                            <span class="input-group-append">
                                <button class="btn bg-teal" type="button" wire:click="pilihAgent">Pilih</button>
                            </span>
                        </div>
                        @error('agent_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Total</label>
                    <div class="col-lg-10">
                        <input type="text"
                            class="form-control {{ $errors->has('total') ? ' border-danger' : null }}"
                            value="{{ $total }}" readonly>
                        @error('total')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" wire:click='Transaksi'
                            class="btn btn-success btn-sm form-control">Transaksi</button>
                    </div>
                </div>
            @else
                <livewire:admin.pages.transaksi.transaksi-jual-pagi-table />
            @endif
        </div>
    </div>
    <livewire:admin.global.konfirmasi-hapus />
    <div wire:ignore.self class="modal fade" id="SelectUserPemohon" tabindex="-1" data-backdrop="static"
        data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{ route('dataproduct') }}" class="btn btn-primary">Tambah Product</a>
                    <hr>
                    <livewire:admin.table.pilih-product />
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="SelectAgent" tabindex="-1" data-backdrop="static"
        data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Agent</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <livewire:admin.table.pilih-agent />
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="PrintOption" tabindex="-1" data-backdrop="static"
        data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-orange">
                    <h5 class="modal-title">Cetak INVOICE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><i class="icon-warning22 mr-3 icon-2x text-danger"></i> Apakah Anda akan cetak invoice?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-warning" data-dismiss="modal">Tidak</button>
                    <button type="button" class="btn bg-primary" wire:click="CetakResi">Ya</button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="ConfrimBatal" tabindex="-1" data-backdrop="static"
        data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-orange">
                    <h5 class="modal-title">Batal Beli ?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="CancelBatalBeli">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><i class="icon-warning22 mr-3 icon-2x text-danger"></i> Apakah Anda yakin akan membatalkan
                        transaksi ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-warning" data-dismiss="modal"
                        wire:click="CancelBatalBeli">Tidak</button>
                    <button type="button" class="btn bg-primary" wire:click="Batal">Ya</button>
                </div>
            </div>
        </div>
    </div>
    @if ($jual)
        <div wire:ignore.self class="modal fade" id="DetailTransaksi" tabindex="-1" data-backdrop="static"
            data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-full" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Transaksi Jual Pagi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Invoice Number:</strong> {{ $jual->invoice }}</p>
                        <p><strong>Invoice Date:</strong> {{ $jual->tanggal }}</p>
                        <p><strong>Nama Agent:</strong> {{ $jual->agent->name }}</p>
                        <p><strong>Status Agent:</strong> {{ $jual->agent->comcode->code_nm }}</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Quantity</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jual->detailTransaksiJual as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right mt-2">
                            <p><strong>Total :</strong> {{ number_format($jual->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>


@push('js')
    <script src="{{ asset('limitless/global_assets/js/demo_pages/form_checkboxes_radios.js') }}"></script>
    <script>
        $(document).ready(function() {
            window.addEventListener('show-select-user-modal', event => {
                $('#SelectUserPemohon').modal('show');
            });

            window.addEventListener('close-select-user-modal', event => {
                $('#SelectUserPemohon').modal('hide');
            });

            window.addEventListener('show-select-agent-modal', event => {
                $('#SelectAgent').modal('show');
            });

            window.addEventListener('close-select-agent-modal', event => {
                $('#SelectAgent').modal('hide');
            });

            window.addEventListener('show-print-modal', event => {
                $('#PrintOption').modal('show');
            });

            window.addEventListener('close-print-modal', event => {
                $('#PrintOption').modal('hide');
            });

            window.addEventListener('show-batal-beli-modal', event => {
                $('#ConfrimBatal').modal('show');
            });

            window.addEventListener('close-batal-beli-modal', event => {
                $('#ConfrimBatal').modal('hide');
            });

            window.addEventListener('show-detail-modal', event => {
                $('#DetailTransaksi').modal('show');
            });
        });

        Livewire.on('cetakInvoice', ($invoice) => {
            var myWindow = window.open(`{{ url('invoice-pagi-print') }}/${$invoice}`, "cetak-pagi-invoice",
                "width=1800,height=5000");

            // Print the contents of the new window
            myWindow.print();

            // Close the new window after a delay of 10,000 milliseconds (10 seconds)
            setTimeout(function() {
                myWindow.close();
            }, 1000);
        });
    </script>
@endpush
