<?php

namespace App\Livewire\Admin\Pages\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DataProduct extends Component
{
    public $idNya, $name, $qty = 0, $satuan, $harga_beli, $harga_jual, $total;
    public $isEdit = false;

    protected $listeners = ['edit', 'delete'];

    public function updatedHargaJual()
    {
        $this->total = $this->qty * $this->harga_jual;
    }

    public function add()
    {
        $this->isEdit = !$this->isEdit;
    }

    public function cancel()
    {
        $this->isEdit = !$this->isEdit;
        $this->idNya = '';
        $this->name = '';
        $this->satuan = '';
        $this->harga_beli = '';
        $this->harga_jual = '';
        $this->qty = 0;
        $this->total = '';
    }

    public function edit($id)
    {
        $this->isEdit = true;
        $data = Product::find($id);
        $this->idNya = $data->id;
        $this->name = $data->name;
        $this->satuan = $data->satuan;
        $this->harga_beli = $data->harga_beli;
        $this->harga_jual = $data->harga_jual;
        $this->qty = $data->qty;
        $this->total = $data->total;
    }
    public function save()
    {
        $rules['name'] = 'required';
        $rules['satuan'] = 'required';
        $rules['harga_beli'] = 'required';
        $rules['harga_jual'] = 'required';
        $this->validate($rules);
        if ($this->idNya) {
            $this->update();
        } else {
            Product::create([
                'name' => $this->name,
                'satuan' => $this->satuan,
                'harga_beli' => $this->harga_beli,
                'harga_jual' => $this->harga_jual,
            ]);
            $this->emit('refreshDatatable');
            $this->cancel();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning'
                'text' => 'Product Berhasil di Tambahkan...', // Isi pesan
            ]);
        }
    }
    public function update()
    {
        $dataUser = Product::find($this->idNya);
        $dataUser->name = $this->name;
        $dataUser->satuan = $this->satuan;
        $dataUser->harga_beli = $this->harga_beli;
        $dataUser->harga_jual = $this->harga_jual;
        $dataUser->total = $this->total;
        $dataUser->save();
        $this->emit('refreshDatatable');
        $this->cancel();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
            'text' => 'Product Berhasil di perbaharui...', // Isi pesan
        ]);
    }

    public function delete($id)
    {
        $this->idNya = $id;
        try {
            DB::transaction(function () {
                $product = Product::find($this->idNya);
                $product->delete();
            });
            $this->emit('refreshDatatable');
            $this->idNya = '';
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Product Berhasil dihapus...', // Isi pesan
            ]);
        } catch (\Exception $e) {
            $this->emit('refreshDatatable');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Product Tidak dapat dihapus...', // Isi pesan
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.pages.product.data-product');
    }
}
