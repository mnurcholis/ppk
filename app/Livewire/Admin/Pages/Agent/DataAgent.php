<?php

namespace App\Livewire\Admin\Pages\Agent;

use App\Models\Agent;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DataAgent extends Component
{
    public $idNya, $name, $number, $address, $status;
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
        $this->status = '';
    }

    public function edit($id)
    {
        $this->isEdit = true;
        $data = Agent::find($id);
        $this->idNya = $data->id;
        $this->name = $data->name;
        $this->number = $data->number;
        $this->address = $data->address;
        $this->status = $data->status;
    }
    public function save()
    {
        $rules['name'] = 'required';
        $rules['number'] = 'required';
        $rules['address'] = 'required';
        $rules['status'] = 'required';
        $this->validate($rules);
        if ($this->idNya) {
            $this->update();
        } else {
            Agent::create([
                'name' => $this->name,
                'number' => $this->number,
                'address' => $this->address,
                'status' => $this->status,
            ]);
            $this->emit('refreshDatatable');
            $this->cancel();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning'
                'text' => 'Agent Berhasil di Tambahkan...', // Isi pesan
            ]);
        }
    }
    public function update()
    {
        $dataUser = Agent::find($this->idNya);
        $dataUser->name = $this->name;
        $dataUser->number = $this->number;
        $dataUser->address = $this->address;
        $dataUser->status = $this->status;
        $dataUser->save();
        $this->emit('refreshDatatable');
        $this->cancel();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
            'text' => 'Agent Berhasil di perbaharui...', // Isi pesan
        ]);
    }

    public function delete($id)
    {
        $this->idNya = $id;
        try {
            DB::transaction(function () {
                $user = Agent::find($this->idNya);
                $user->delete();
            });
            $this->emit('refreshDatatable');
            $this->idNya = '';
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Agent Berhasil dihapus...', // Isi pesan
            ]);
        } catch (\Exception $e) {
            $this->emit('refreshDatatable');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Agent Tidak dapat dihapus...', // Isi pesan
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.pages.agent.data-agent');
    }
}
