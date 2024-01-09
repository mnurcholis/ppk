<div>
    <x-slot name="header">
        <livewire:admin.global.page-header judul="Data" subjudul="Karyawan" :breadcrumb="['Data Karyawan']" />
    </x-slot>
    <div class="card">
        <div class="card-header">
            @if ($isEdit)
                <a href="#" wire:click='cancel' class="btn btn-warning mt-md-0 mt-2 ml-md-8">Cancel</a>
            @else
                <a href="#" wire:click='add' class="btn btn-primary mt-md-0 mt-2">Tambah Gaji</a>
            @endif

        </div>
        <div class="card-body">
            @if ($isEdit)
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Pilih Karyawan:</label><br>
                            <button class="btn btn-primary" wire:click="pilihKaryawan" type="button">Pilih</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nama Karyawan:</label>
                            <input type="text"
                                class="form-control {{ $errors->has('karyawan_id') ? ' border-danger' : null }}"
                                value="{{ $karyawan->name ?? '' }}" disabled>
                            @error('karyawan_id')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nomor Telephon:</label>
                            <input type="text"
                                class="form-control {{ $errors->has('karyawan_id') ? ' border-danger' : null }}"
                                value="{{ $karyawan->number ?? '' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nama Bank:</label>
                            <input type="text"
                                class="form-control {{ $errors->has('karyawan_id') ? ' border-danger' : null }}"
                                value="{{ $karyawan->bank ?? '' }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nomor Rekening:</label>
                            <input type="text"
                                class="form-control {{ $errors->has('karyawan_id') ? ' border-danger' : null }}"
                                value="{{ $karyawan->account ?? '' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Alamat:</label>
                        <input type="text"
                            class="form-control {{ $errors->has('karyawan_id') ? ' border-danger' : null }}"
                            value="{{ $karyawan->address ?? '' }}" disabled>
                    </div>
                    <div class="col-md-3">
                        <label>Status Karyawan:</label>
                        <input type="text"
                            class="form-control {{ $errors->has('karyawan_id') ? ' border-danger' : null }}"
                            value="{{ $karyawan->comcode->code_nm ?? '' }}" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label>Gaji:</label>
                        {{ Form::number(null, null, [
                            'class' => 'form-control' . ($errors->has('gaji') ? ' border-danger' : null),
                            'placeholder' => 'Gaji',
                            'wire:model' => 'gaji',
                        ]) }}
                        @error('gaji')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Status Karyawan:</label>
                        {{ Form::select(null, get_code_group('STATUS_GAJI'), null, [
                            'class' => 'form-control' . ($errors->has('kategori') ? ' border-danger' : null),
                            'placeholder' => 'Pilih Jenis Gaji',
                            'wire:model' => 'kategori',
                        ]) }}
                        @error('kategori')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Bonus:</label>
                        {{ Form::number(null, null, [
                            'class' => 'form-control' . ($errors->has('bonus') ? ' border-danger' : null),
                            'placeholder' => 'Bonus',
                            'wire:model' => 'bonus',
                        ]) }}
                        @error('bonus')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Total:</label>
                        {{ Form::number(null, null, [
                            'class' => 'form-control' . ($errors->has('total') ? ' border-danger' : null),
                            'placeholder' => 'Total',
                            'wire:model' => 'total',
                            'disabled' => 'disabled',
                        ]) }}
                        @error('total')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3">
                        <button type="submit" wire:click='save' class="btn btn-primary mt-md-0 mt-2">Save</button>
                    </div>
                </div>
                <hr>
            @else
                <livewire:admin.pages.karyawan.gaji-karyawan-table />
            @endif
        </div>
    </div>
    <livewire:admin.global.konfirmasi-hapus />
    <div wire:ignore.self class="modal fade" id="SelectUserPemohon" tabindex="-1" data-backdrop="static"
        data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{ route('karyawan') }}" class="btn btn-primary">Tambah Karyawan</a>
                    <hr>
                    <livewire:admin.table.pilih-karyawan />
                </div>
            </div>
        </div>
    </div>
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
        });
    </script>
@endpush
