<div>
    <x-slot name="header">
        <livewire:admin.global.page-header judul="Data" subjudul="Karyawan" :breadcrumb="['Data Karyawan']" />
    </x-slot>
    <div class="card">
        <div class="card-header">
            @if ($isEdit)
                <a href="#" wire:click='cancel' class="btn btn-warning mt-md-0 mt-2 ml-md-8">Cancel</a>
            @else
                <a href="#" wire:click='add' class="btn btn-primary mt-md-0 mt-2">Create Karyawan</a>
            @endif

        </div>
        <div class="card-body">
            @if ($isEdit)
                <h6>Account Details</h6>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nama Karyawan:</label>
                            {{ Form::text(null, null, [
                                'class' => 'form-control' . ($errors->has('name') ? ' border-danger' : null),
                                'placeholder' => 'Nama Karyawan',
                                'wire:model' => 'name',
                            ]) }}
                            @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nomor Telephon:</label>
                            {{ Form::text(null, null, [
                                'class' => 'form-control' . ($errors->has('number') ? ' border-danger' : null),
                                'placeholder' => 'Nomor Telepon',
                                'wire:model' => 'number',
                            ]) }}
                            @error('number')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nama Bank:</label>
                            {{ Form::text(null, null, [
                                'class' => 'form-control' . ($errors->has('bank') ? ' border-danger' : null),
                                'placeholder' => 'Nama Bank',
                                'wire:model' => 'bank',
                            ]) }}
                            @error('bank')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nomor Rekening:</label>
                            {{ Form::text(null, null, [
                                'class' => 'form-control' . ($errors->has('account') ? ' border-danger' : null),
                                'placeholder' => 'Nomor Rekening',
                                'wire:model' => 'account',
                            ]) }}
                            @error('account')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <label>Alamat:</label>
                        {{ Form::text(null, null, [
                            'class' => 'form-control' . ($errors->has('address') ? ' border-danger' : null),
                            'placeholder' => 'Alamat Lengkap',
                            'wire:model' => 'address',
                        ]) }}
                        @error('address')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Status Karyawan:</label>
                        {{ Form::select(null, get_code_group('STATUS_KARYAWAN'), null, [
                            'class' => 'form-control' . ($errors->has('status') ? ' border-danger' : null),
                            'placeholder' => 'Pilih Status Karyawan',
                            'wire:model' => 'status',
                        ]) }}
                        @error('status')
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
                <livewire:admin.pages.karyawan.data-karyawan-table />
            @endif
        </div>
    </div>
    <livewire:admin.global.konfirmasi-hapus />
</div>
