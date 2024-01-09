<div>
    <x-slot name="header">
        <livewire:admin.global.page-header judul="Data" subjudul="Operasional" :breadcrumb="['Data Operasional']" />
    </x-slot>
    <div class="card">
        <div class="card-header">
            @if ($isEdit)
                <a href="#" wire:click='cancel' class="btn btn-warning mt-md-0 mt-2 ml-md-8">Cancel</a>
            @else
                <a href="#" wire:click='add' class="btn btn-primary mt-md-0 mt-2">Create Operasional</a>
            @endif

        </div>
        <div class="card-body">
            @if ($isEdit)
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Operasional:</label>
                            {{ Form::text(null, null, [
                                'class' => 'form-control' . ($errors->has('name') ? ' border-danger' : null),
                                'placeholder' => 'Nama Operasional',
                                'wire:model' => 'name',
                            ]) }}
                            @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total Biaya:</label>
                            {{ Form::number(null, null, [
                                'class' => 'form-control' . ($errors->has('total') ? ' border-danger' : null),
                                'placeholder' => 'Total Biaya',
                                'wire:model' => 'total',
                            ]) }}
                            @error('total')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Keterangan:</label>
                        {{ Form::text(null, null, [
                            'class' => 'form-control' . ($errors->has('keterangan') ? ' border-danger' : null),
                            'placeholder' => 'Keterangan Kegiatan',
                            'wire:model' => 'keterangan',
                        ]) }}
                        @error('keterangan')
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
                <livewire:admin.pages.operasional.data-opersional-table />
            @endif
        </div>
    </div>
    <livewire:admin.global.konfirmasi-hapus />
</div>
