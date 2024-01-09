<?php

namespace App\Livewire\Admin\Pages\Karyawan;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Karyawan;

class DataKaryawanTable extends DataTableComponent
{
    protected $model = Karyawan::class;

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
            Column::make("Bank", "bank")
                ->sortable()->searchable(),
            Column::make("No Rekening", "account")
                ->sortable()->searchable(),
            Column::make("Status", "comcode.code_nm")
                ->sortable()->searchable(),
            Column::make('Action', 'id')->view('components.table-action'),
        ];
    }
}
