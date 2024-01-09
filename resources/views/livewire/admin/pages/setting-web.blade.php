<div>
    <x-slot name="header">
        <livewire:admin.global.page-header judul="Setting" subjudul="Web" :breadcrumb="[]" />
    </x-slot>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Setting Website</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="reload"></a>
                    <a class="list-icons-item" data-action="remove"></a>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="simpan" method="POST">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">title :</label>
                    <div class="col-md-8">
                        {{ Form::text(null, null, [
                            'class' => 'form-control' . ($errors->has('title') ? ' border-danger' : null),
                            'placeholder' => 'Judul',
                            'wire:model.lazy' => 'title',
                        ]) }}
                        @error('title')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Slogan Web :</label>
                    <div class="col-md-8">
                        {{ Form::text(null, null, [
                            'class' => 'form-control' . ($errors->has('slogan') ? ' border-danger' : null),
                            'wire:model.lazy' => 'slogan',
                        ]) }}
                        @error('slogan')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Diskripsi Web :</label>
                    <div class="col-md-8">
                        {{ Form::textarea(null, null, [
                            'class' => 'form-control' . ($errors->has('deskripsi_situs') ? ' border-danger' : null),
                            'wire:model.lazy' => 'deskripsi_situs',
                            'size' => '50x3',
                        ]) }}
                        @error('deskripsi_situs')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Logo :</label>
                    <div class="col-md-6">
                        @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" width="200"
                                class="img-fluid mx-auto d-block float-left m-2">
                        @elseif ($edit_logo != '')
                            <img src="{{ route('helper.show-picture', ['path' => $edit_logo]) }}" width="200"
                                height="200" class="img-fluid mx-auto d-block float-left m-2">
                        @endif
                        <input type="file" wire:model="logo" accept="image/png, image/jpeg" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Favicon :</label>
                    <div class="col-md-6">
                        @if ($favicon)
                            <img src="{{ $favicon->temporaryUrl() }}" width="200"
                                class="img-fluid mx-auto d-block float-left m-2">
                        @elseif ($edit_favicon != '')
                            <img src="{{ route('helper.show-picture', ['path' => $edit_favicon]) }}" width="200"
                                height="40" class="img-fluid mx-auto d-block float-left m-2">
                        @endif
                        <input type="file" wire:model="favicon" accept="image/png, image/jpeg" class="form-control">
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <button type="submit" class="btn btn-primary text-right">Save</button>
                    </li>
                </ul>
            </div>
        </form>
    </div>
</div>
