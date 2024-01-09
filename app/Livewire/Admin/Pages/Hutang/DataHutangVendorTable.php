<?php

namespace App\Livewire\Admin\Pages\Hutang;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TransaksiBeli;
use Illuminate\Database\Eloquent\Builder;

class DataHutangVendorTable extends DataTableComponent
{
    protected $model = TransaksiBeli::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return TransaksiBeli::where('sisa', '>', 0)
            ->orWhereHas('HutangVendor', function ($query) {
                $query->where('bayar', '>', 0);
            });
    }

    public function Bayar($id)
    {
        $this->emit('Bayar', $id);
    }

    public function Detail($id)
    {
        $this->emit('Detail', $id);
    }

    public function columns(): array
    {
        return [
            Column::make("Vendor", "vendor.name"),
            Column::make("Invoice", "invoice")
                ->sortable(),
            Column::make("Tanggal", "tanggal")
                ->sortable(),
            Column::make('Piutang', 'total')
                ->format(
                    function ($value, $row, Column $column) {
                        return number_format($row->total, 0, ',', '.');
                    }
                )
                ->html(),
            Column::make('Bayar', 'bayar')
                ->format(
                    function ($value, $row, Column $column) {
                        return number_format($row->bayar, 0, ',', '.');
                    }
                )
                ->html(),
            Column::make('Hutang', 'sisa')
                ->format(
                    function ($value, $row, Column $column) {
                        return number_format($row->sisa, 0, ',', '.');
                    }
                )
                ->html(),
            Column::make('Action', 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return '
                        <button type="button" class="btn btn-outline-info btn-sm" wire:click="Detail(' . $row->id . ')")"><i class="icon-eye"></i></button>
                        <button type="button" class="btn btn-outline-warning btn-sm" wire:click="Bayar(' . $row->id . ')")"><i class="icon-cart4"></i> Bayar</button>
                        ';
                    }
                )
                ->html(),
        ];
    }
}
