<?php

namespace App\Livewire\Admin\Pages\Vendor;

use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DataVendor extends Component
{
    public $idNya, $name, $number, $address;
    public $isEdit = false;

    protected $listeners = ['edit', 'delete'];

    public function add()
    {
        $this->isEdit = !$this->isEdit;
    }

    public function cancel()
    {
        $this->isEdit = !$this->isEdit;
        $this->idNya = '';
        $this->name = '';
        $this->number = '';
        $this->address = '';
    }

    public function edit($id)
    {
        $this->isEdit = true;
        $data = Vendor::find($id);
        $this->idNya = $data->id;
        $this->name = $data->name;
        $this->number = $data->number;
        $this->address = $data->address;
    }

    public function save()
    {
        $rules['name'] = 'required';
        $rules['number'] = 'required';
        $rules['address'] = 'required';
        $this->validate($rules);
        if ($this->idNya) {
            $this->update();
        } else {
            Vendor::create([
                'name' => $this->name,
                'number' => $this->number,
                'address' => $this->address,
            ]);
            $this->emit('refreshDatatable');
            $this->cancel();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning'
                'text' => 'Vendor Berhasil di Tambahkan...', // Isi pesan
            ]);
        }
    }
    public function update()
    {
        $dataUser = Vendor::find($this->idNya);
        $dataUser->name = $this->name;
        $dataUser->number = $this->number;
        $dataUser->address = $this->address;
        $dataUser->save();
        $this->emit('refreshDatatable');
        $this->cancel();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
            'text' => 'Vendor Berhasil di perbaharui...', // Isi pesan
        ]);
    }

    public function delete($id)
    {
        $this->idNya = $id;
        try {
            DB::transaction(function () {
                $user = Vendor::find($this->idNya);
                $user->delete();
            });
            $this->emit('refreshDatatable');
            $this->idNya = '';
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Vendor Berhasil dihapus...', // Isi pesan
            ]);
        } catch (\Exception $e) {
            $this->emit('refreshDatatable');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Vendor Tidak dapat dihapus...', // Isi pesan
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.pages.vendor.data-vendor');
    }
}
