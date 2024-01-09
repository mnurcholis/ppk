<?php

namespace App\Livewire\Admin\Pages\Hutang;

use App\Models\HutangAgent;
use App\Models\HutangAgentDetail;
use App\Models\TransaksiJualSore;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DataHutangAgent extends Component
{
    public $idNya, $idBayar, $transaksi, $bayar, $beli;
    public $isEdit = false;
    public $isBayar = false;

    protected $listeners = ['Bayar', 'Detail'];

    public function Bayar($id)
    {
        $this->idNya = $id;
        $this->transaksi = TransaksiJualSore::find($id);
        $this->isEdit = true;
    }

    public function CancelTambahBayar()
    {
        $this->idNya = null;
        $this->transaksi = null;
        $this->isEdit = false;
        $this->emit('refreshDatatable');
    }

    public function reloadBayar()
    {
        $this->transaksi = TransaksiJualSore::find($this->idNya);
    }

    public function TambahBayar()
    {
        if ($this->transaksi->sisa == 0) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Hutang Sudah Lunas...', // Isi pesan
            ]);
        } else {
            $this->isBayar = true;
        }
    }

    public function BatalBayar()
    {
        $this->isBayar = false;
    }

    public function SimpanBayar()
    {
        $rules['bayar'] = 'required';
        $this->validate($rules);
        if ($this->bayar > $this->transaksi->sisa) {
            throw ValidationException::withMessages([
                'bayar' => 'Bayar Melebihi Hutang...',
            ]);
        }

        try {
            DB::transaction(function () {
                if ($this->transaksi->HutangAgent) {
                    $b = HutangAgent::find($this->transaksi->HutangAgent->id);
                    $b->update([
                        'bayar' => $b->bayar + $this->bayar,
                        'sisa' => $b->sisa - $this->bayar,
                    ]);
                } else {
                    $b = HutangAgent::create([
                        'agent_id' => $this->transaksi->agent_id,
                        'transaksi_jual_sore_id' => $this->transaksi->id,
                        'awal' => $this->transaksi->sisa,
                        'bayar' => $this->bayar,
                        'sisa' => $this->transaksi->sisa - $this->bayar,
                    ]);
                }

                $c = HutangAgentDetail::create([
                    'hutang_agent_id' => $b->id,
                    'bayar' => $this->bayar,
                    'tanggal' => date('Y-m-d'),
                ]);

                $this->transaksi->sisa = $this->transaksi->sisa - $this->bayar;
                $this->transaksi->bayar = $this->transaksi->bayar + $this->bayar;
                $this->transaksi->save();
            });
            $this->isBayar = false;
            $this->bayar = null;
            $this->reloadBayar();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Pembayaran Berhasil ditambahkan...', // Isi pesan
            ]);
        } catch (\Exception $e) {
            dd($e);
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Proses Bayar Error...', // Isi pesan
            ]);
        }
    }

    public function BatalTambahBayar($id, $bayar)
    {
        $this->idBayar = $id;
        $this->bayar = $bayar;
        try {
            DB::transaction(function () {
                $b = HutangAgent::find($this->transaksi->HutangAgent->id);
                $b->update([
                    'bayar' => $b->bayar - $this->bayar,
                    'sisa' => $b->sisa + $this->bayar,
                ]);

                HutangAgentDetail::find($this->idBayar)->delete();

                $this->transaksi->sisa = $this->transaksi->sisa + $this->bayar;
                $this->transaksi->bayar = $this->transaksi->bayar - $this->bayar;
                $this->transaksi->save();
            });
            $this->idBayar = null;
            $this->bayar = null;
            $this->reloadBayar();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Pembayaran Berhasil dibatalkan...', // Isi pesan
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Proses Hapus Error...', // Isi pesan
            ]);
        }
    }

    public function Detail($id)
    {
        $this->beli = TransaksiJualSore::find($id);
        $this->dispatchBrowserEvent('show-detail-modal');
    }

    public function render()
    {
        return view('livewire.admin.pages.hutang.data-hutang-agent');
    }
}
