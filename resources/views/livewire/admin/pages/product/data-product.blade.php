<div>
    <x-slot name="header">
        <livewire:admin.global.page-header judul="Data" subjudul="Product" :breadcrumb="['Data Product']" />
    </x-slot>
    <div class="card">
        <div class="card-header">
            @if ($isEdit)
                <a href="#" wire:click='cancel' class="btn btn-warning mt-md-0 mt-2 ml-md-8">Cancel</a>
            @else
                <a href="#" wire:click='add' class="btn btn-primary mt-md-0 mt-2">Add Product</a>
            @endif

        </div>
        <div class="card-body">
            @if ($isEdit)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama:</label>
                            {{ Form::text(null, null, [
                                'class' => 'form-control' . ($errors->has('name') ? ' border-danger' : null),
                                'placeholder' => 'Nama',
                                'wire:model' => 'name',
                            ]) }}
                            @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Satuan:</label>
                            {{ Form::text(null, null, [
                                'class' => 'form-control' . ($errors->has('satuan') ? ' border-danger' : null),
                                'placeholder' => 'Satuan',
                                'wire:model' => 'satuan',
                            ]) }}
                            @error('satuan')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Harga Beli:</label>
                            {{ Form::number(null, null, [
                                'class' => 'form-control' . ($errors->has('harga_beli') ? ' border-danger' : null),
                                'placeholder' => 'Harga Beli',
                                'wire:model' => 'harga_beli',
                            ]) }}
                            @error('harga_beli')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Harga Jual:</label>
                            {{ Form::number(null, null, [
                                'class' => 'form-control' . ($errors->has('harga_jual') ? ' border-danger' : null),
                                'placeholder' => 'Harga Jual',
                                'wire:model' => 'harga_jual',
                            ]) }}
                            @error('harga_jual')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Total:</label>
                        {{ Form::text(null, null, [
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
                        <button type="submit" wire:click='save' class="btn btn-primary mt-md-0 mt-2">Simpan</button>
                    </div>
                </div>
                <hr>
            @else
                <livewire:admin.pages.product.data-product-table />
            @endif
        </div>
    </div>
    <livewire:admin.global.konfirmasi-hapus />
</div>
