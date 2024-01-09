<?php

namespace App\Livewire\Admin\Pages\Karyawan;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Gaji;

class GajiKaryawanTable extends DataTableComponent
{
    protected $model = Gaji::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "karyawan.name")
                ->sortable()->searchable(),
            Column::make("Nama", "karyawan.address")
                ->sortable()->searchable(),
            Column::make("Number", "karyawan.number")
                ->sortable()->searchable(),
            Column::make("Rekening", "karyawan.account")
                ->sortable()->searchable(),
            Column::make("Gaji", "gaji")
                ->sortable()->searchable(),
            Column::make("Bonus", "bonus")
                ->sortable()->searchable(),
            Column::make('Action', 'id')->view('components.table-action'),
        ];
    }
}
