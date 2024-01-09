<?php

namespace App\Livewire\Admin\Pages\Product;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;

class DataProductTable extends DataTableComponent
{
    protected $model = Product::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "name")
                ->sortable()->searchable(),
            Column::make('Beli', 'harga_beli')
                ->sortable()->searchable()
                ->format(
                    function ($value, $row, Column $column) {
                        return number_format($row->harga_beli, 0, ',', '.');
                    }
                )
                ->html(),
            Column::make('Harga Jual', 'harga_jual')
                ->sortable()->searchable()
                ->format(
                    function ($value, $row, Column $column) {
                        return number_format($row->harga_jual, 0, ',', '.');
                    }
                )
                ->html(),
            Column::make("Qty", "qty")
                ->sortable()->searchable(),
            Column::make("Satuan", "satuan")
                ->sortable()->searchable(),
            Column::make('Total', 'total')
                ->sortable()->searchable()
                ->format(
                    function ($value, $row, Column $column) {
                        return number_format($row->total, 0, ',', '.');
                    }
                )
                ->html(),
            Column::make('Action', 'id')->view('components.table-action'),
        ];
    }
}
