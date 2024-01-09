<?php

namespace App\Livewire\Admin\Table;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Karyawan;

class PilihKaryawan extends DataTableComponent
{
    protected $model = Karyawan::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function PilihUser($user_id)
    {
        $this->emit('SelectUserPemohon', $user_id);
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
            Column::make('Action', 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return '<button href="#" class="btn btn-primary" wire:click="PilihUser(' . $row->id . ')")">Pilih</button>';
                    }
                )
                ->html(),
        ];
    }
}
