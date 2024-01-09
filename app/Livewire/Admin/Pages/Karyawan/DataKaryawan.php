<?php

namespace App\Livewire\Admin\Pages\Karyawan;

use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DataKaryawan extends Component
{
    public $idNya, $name, $number, $address, $bank, $account, $status;
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
        $this->bank = '';
        $this->account = '';
        $this->status = '';
    }

    public function edit($id)
    {
        $this->isEdit = true;
        $data = Karyawan::find($id);
        $this->idNya = $data->id;
        $this->name = $data->name;
        $this->number = $data->number;
        $this->address = $data->address;
        $this->bank = $data->bank;
        $this->account = $data->account;
        $this->status = $data->status;
    }
    public function save()
    {
        $rules['name'] = 'required';
        $rules['number'] = 'required';
        $rules['address'] = 'required';
        $rules['bank'] = 'required';
        $rules['account'] = 'required';
        $rules['status'] = 'required';
        $this->validate($rules);
        if ($this->idNya) {
            $this->update();
        } else {
            Karyawan::create([
                'name' => $this->name,
                'number' => $this->number,
                'address' => $this->address,
                'bank' => $this->bank,
                'account' => $this->account,
                'status' => $this->status,
            ]);
            $this->emit('refreshDatatable');
            $this->cancel();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning'
                'text' => 'Karyawan Berhasil di Tambahkan...', // Isi pesan
            ]);
        }
    }
    public function update()
    {
        $dataUser = Karyawan::find($this->idNya);
        $dataUser->name = $this->name;
        $dataUser->number = $this->number;
        $dataUser->address = $this->address;
        $dataUser->bank = $this->bank;
        $dataUser->account = $this->account;
        $dataUser->status = $this->status;
        $dataUser->save();
        $this->emit('refreshDatatable');
        $this->cancel();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
            'text' => 'Karyawan Berhasil di perbaharui...', // Isi pesan
        ]);
    }

    public function delete($id)
    {
        $this->idNya = $id;
        try {
            DB::transaction(function () {
                $user = Karyawan::find($this->idNya);
                $user->delete();
            });
            $this->emit('refreshDatatable');
            $this->idNya = '';
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Karyawan Berhasil dihapus...', // Isi pesan
            ]);
        } catch (\Exception $e) {
            $this->emit('refreshDatatable');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Karyawan Tidak dapat dihapus...', // Isi pesan
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.pages.karyawan.data-karyawan');
    }
}
