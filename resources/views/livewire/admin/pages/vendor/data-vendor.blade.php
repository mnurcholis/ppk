<div>
    <x-slot name="header">
        <livewire:admin.global.page-header judul="Data" subjudul="Vendor" :breadcrumb="['Data Vendor']" />
    </x-slot>
    <div class="card">
        <div class="card-header">
            @if ($isEdit)
                <a href="#" wire:click='cancel' class="btn btn-warning mt-md-0 mt-2 ml-md-8">Cancel</a>
            @else
                <a href="#" wire:click='add' class="btn btn-primary mt-md-0 mt-2">Create Vendor</a>
            @endif

        </div>
        <div class="card-body">
            @if ($isEdit)
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Vendor:</label>
                            {{ Form::text(null, null, [
                                'class' => 'form-control' . ($errors->has('name') ? ' border-danger' : null),
                                'placeholder' => 'Nama Vendor',
                                'wire:model' => 'name',
                            ]) }}
                            @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Telephone:</label>
                            {{ Form::text(null, null, [
                                'class' => 'form-control' . ($errors->has('number') ? ' border-danger' : null),
                                'placeholder' => 'Nomor Telephone',
                                'wire:model' => 'number',
                            ]) }}
                            @error('number')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
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
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3">
                        <button type="submit" wire:click='save' class="btn btn-primary mt-md-0 mt-2">Save</button>
                    </div>
                </div>
                <hr>
            @else
                <livewire:admin.pages.vendor.data-vendor-table />
            @endif
        </div>
    </div>
    <livewire:admin.global.konfirmasi-hapus />
</div>
