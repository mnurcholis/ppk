<?php

namespace App\Livewire\Admin\Pages\Vendor;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Vendor;

class DataVendorTable extends DataTableComponent
{
    protected $model = Vendor::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "name")
                ->sortable()->searchable(),
            Column::make("Nomor", "number")
                ->sortable()->searchable(),
            Column::make("Alamat", "address")
                ->sortable()->searchable(),
            Column::make('Action', 'id')->view('components.table-action'),
        ];
    }
}
