<div>
    <x-slot name="header">
        <livewire:admin.global.page-header judul="Data Hutang" subjudul="Vendor" :breadcrumb="['Hutang Vendor']" />
    </x-slot>

    <div class="card">
        <div class="card-body">
            @if ($isEdit)
                <div class="row">
                    <div class="col-md-2 pr-0">
                        <div class="form-group">
                            <label>Invoice:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->invoice ?? '' }}" readonly>
                            @error('product_id')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 pl-0 pr-0">
                        <div class="form-group">
                            <label>Tanggal:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->tanggal ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 pl-0 pr-0">
                        <div class="form-group">
                            <label>Total:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->total ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 pl-0 pr-0">
                        <div class="form-group">
                            <label>Bayar:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->bayar ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 pl-0 pr-0">
                        <div class="form-group">
                            <label>Sisa:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->sisa ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label></label><br>
                            <button class="form-control btn btn-primary btn-sm mt-2" wire:click="TambahBayar"
                                type="button">Tambah</button>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label></label><br>
                            <button class="form-control btn btn-danger btn-sm mt-2" wire:click="CancelTambahBayar"
                                type="button">Kembali</button>
                        </div>
                    </div>
                </div>
                @if ($isBayar)
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Bayar</label>
                        <div class="col-lg-6">
                            <input type="number" class="form-control" wire:model="bayar" min="1">
                            @error('bayar')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-2">
                            <button class="form-control btn btn-warning" wire:click="SimpanBayar"
                                type="button">Simpan</button>
                        </div>
                        <div class="col-lg-2">
                            <button class="form-control btn btn-danger" wire:click="BatalBayar"
                                type="button">Batal</button>
                        </div>
                    </div>
                @endif
                @if ($transaksi->HutangAgent)
                    <div class="row">
                        <div class="col-md-2 pr-0">
                            <div class="form-group">
                                <label>Hutang Awal:</label>
                                <input type="text" class="form-control"
                                    value="{{ $transaksi->HutangAgent->awal ?? '' }}" readonly>
                                @error('product_id')
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2 pl-0 pr-0">
                            <div class="form-group">
                                <label>Bayar :</label>
                                <input type="text" class="form-control"
                                    value="{{ $transaksi->HutangAgent->bayar ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-2 pl-0 pr-0">
                            <div class="form-group">
                                <label>Sisa :</label>
                                <input type="text" class="form-control"
                                    value="{{ $transaksi->HutangAgent->sisa ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-table table-responsive shadow-0 mb-0 mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal</th>
                                            <th>Bayar</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $jumlah = 0;
                                        @endphp
                                        @foreach ($transaksi->HutangAgent->HutangAgentDetail as $a)
                                            <tr>
                                                <td>{{ $a['id'] }}</td>
                                                <td>{{ $a['tanggal'] }}</td>
                                                <td>{{ $a['bayar'] }}</td>
                                                <td><button type="button"
                                                        class="btn bg-pink-400 btn-icon rounded-round btn-sm"
                                                        wire:click="BatalTambahBayar({{ $a['id'] }},{{ $a['bayar'] }})"><i
                                                            class="icon-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                            @php
                                                $jumlah += $a['bayar'];
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="2"></td>
                                            <td><b>{{ $jumlah }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @error('list_add_product')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif

        </div>
    @else
        <livewire:admin.pages.hutang.data-hutang-agent-table>
            @endif
            @if ($beli)
                <div wire:ignore.self class="modal fade" id="DetailTransaksi" tabindex="-1" data-backdrop="static"
                    data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog modal-full" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Transaksi Jual Sore</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Invoice Number:</strong> {{ $beli->invoice }}</p>
                                <p><strong>Invoice Date:</strong> {{ $beli->tanggal }}</p>
                                <p><strong>Nama Agent:</strong> {{ $beli->agent->name }}</p>
                                <p class="mt-2"><strong>Detail Transaksi:</strong></p>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Barang</th>
                                            <th>Quantity</th>
                                            <th>Harga Beli</th>
                                            <th>Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($beli->detailTransaksiJual as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ $item->qty_keluar }}</td>
                                                <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                                <td>{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p class="mt-2"><strong>Detail HUtang:</strong></p>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Awal Hutang</th>
                                            <th>Bayar</th>
                                            <th>Sisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ number_format($beli->HutangAgent->awal ?? $beli->total, 0, ',', '.') }}
                                            </td>
                                            <td>{{ number_format($beli->HutangAgent->bayar ?? $beli->bayar, 0, ',', '.') }}
                                            </td>
                                            <td>{{ number_format($beli->HutangAgent->sisa ?? $beli->sisa, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p class="mt-2"><strong>Detail Pembayaran:</strong></p>
                                @if ($beli->HutangAgent)
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Bayar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($beli->HutangAgent->HutangAgentDetail as $val)
                                                <tr>
                                                    <td>{{ $val->tanggal }}</td>
                                                    <td>{{ number_format($val->bayar, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>Belum ada pembayaran</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            window.addEventListener('show-detail-modal', event => {
                $('#DetailTransaksi').modal('show');
            });
        });
    </script>
@endpush
