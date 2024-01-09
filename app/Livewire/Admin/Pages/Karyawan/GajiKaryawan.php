<?php

namespace App\Livewire\Admin\Pages\Karyawan;

use App\Models\Gaji;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GajiKaryawan extends Component
{
    public $idNya, $karyawan_id, $karyawan, $gaji, $bonus, $kategori, $total;
    public $isEdit = false;

    protected $listeners = ['SelectUserPemohon', 'edit', 'delete'];

    public function updatedGaji()
    {
        $this->total = $this->gaji + $this->bonus;
    }

    public function updatedBonus()
    {
        $this->total = $this->gaji + $this->bonus;
    }

    public function add()
    {
        $this->isEdit = !$this->isEdit;
    }

    public function cancel()
    {
        $this->isEdit = !$this->isEdit;
        $this->idNya = null;
        $this->karyawan_id = null;
        $this->karyawan = null;
        $this->gaji = null;
        $this->bonus = null;
        $this->kategori = null;
        $this->total = null;
    }

    public function edit($id)
    {
        $this->isEdit = true;
        $data = Gaji::find($id);
        $this->idNya = $data->id;
        $this->karyawan_id = $data->karyawan_id;
        $this->karyawan = Karyawan::find($this->karyawan_id);
        $this->gaji = $data->gaji;
        $this->bonus = $data->bonus;
        $this->kategori = $data->kategori;
        $this->total = $this->gaji + $this->bonus;
    }
    public function save()
    {
        $rules['karyawan_id'] = 'required';
        $rules['gaji'] = 'required';
        $rules['kategori'] = 'required';
        $this->validate($rules);
        if ($this->idNya) {
            $this->update();
        } else {
            Gaji::create([
                'karyawan_id' => $this->karyawan_id,
                'gaji' => $this->gaji,
                'bonus' => $this->bonus,
                'kategori' => $this->kategori,
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
        $dataUser = Gaji::find($this->idNya);
        $dataUser->karyawan_id = $this->karyawan_id;
        $dataUser->gaji = $this->gaji;
        $dataUser->bonus = $this->bonus;
        $dataUser->kategori = $this->kategori;
        $dataUser->save();
        $this->emit('refreshDatatable');
        $this->cancel();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
            'text' => 'Gaji Berhasil di perbaharui...', // Isi pesan
        ]);
    }

    public function delete($id)
    {
        $this->idNya = $id;
        try {
            DB::transaction(function () {
                $user = Gaji::find($this->idNya);
                $user->delete();
            });
            $this->emit('refreshDatatable');
            $this->idNya = '';
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Gaji Berhasil dihapus...', // Isi pesan
            ]);
        } catch (\Exception $e) {
            $this->emit('refreshDatatable');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Gaji Tidak dapat dihapus...', // Isi pesan
            ]);
        }
    }

    public function pilihKaryawan()
    {
        $this->dispatchBrowserEvent('show-select-user-modal');
    }

    public function SelectUserPemohon($id)
    {
        $this->karyawan_id = $id;
        $this->karyawan = Karyawan::find($id);
        $this->dispatchBrowserEvent('close-select-user-modal');
    }

    public function render()
    {
        return view('livewire.admin.pages.karyawan.gaji-karyawan');
    }
}
