<?php

namespace App\Livewire\Admin\Pages\Operasional;

use App\Models\Operasional;
use Livewire\Component;

class DataOpersional extends Component
{
    public $idNya, $name, $total, $keterangan;
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
        $this->total = '';
        $this->keterangan = '';
    }

    public function edit($id)
    {
        $this->isEdit = true;
        $data = Operasional::find($id);
        $this->idNya = $data->id;
        $this->name = $data->name;
        $this->total = $data->total;
        $this->keterangan = $data->keterangan;
    }

    public function save()
    {
        $rules['name'] = 'required';
        $rules['total'] = 'required';
        $rules['keterangan'] = 'required';
        $this->validate($rules);
        if ($this->idNya) {
            $this->update();
        } else {
            Operasional::create([
                'name' => $this->name,
                'total' => $this->total,
                'keterangan' => $this->keterangan,
            ]);
            $this->emit('refreshDatatable');
            $this->cancel();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning'
                'text' => 'Operasional Berhasil di Tambahkan...', // Isi pesan
            ]);
        }
    }
    public function update()
    {
        $dataUser = Operasional::find($this->idNya);
        $dataUser->name = $this->name;
        $dataUser->total = $this->total;
        $dataUser->keterangan = $this->keterangan;
        $dataUser->save();
        $this->emit('refreshDatatable');
        $this->cancel();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
            'text' => 'Operasional Berhasil di perbaharui...', // Isi pesan
        ]);
    }

    public function delete($id)
    {
        $user = Operasional::find($id);
        $user->delete();
        $this->emit('refreshDatatable');
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
            'text' => 'Operasional Berhasil dihapus...', // Isi pesan
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pages.operasional.data-opersional');
    }
}
