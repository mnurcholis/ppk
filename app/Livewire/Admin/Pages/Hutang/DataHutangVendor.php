<?php

namespace App\Livewire\Admin\Pages\Hutang;

use App\Models\HutangVendor;
use App\Models\HutangVendorDetail;
use App\Models\TransaksiBeli;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DataHutangVendor extends Component
{
    public $idNya, $idBayar, $transaksibeli, $bayar, $beli;
    public $isEdit = false;
    public $isBayar = false;

    protected $listeners = ['Bayar', 'Detail'];

    public function Bayar($id)
    {
        $this->idNya = $id;
        $this->transaksibeli = TransaksiBeli::find($id);
        $this->isEdit = true;
    }

    public function CancelTambahBayar()
    {
        $this->idNya = null;
        $this->transaksibeli = null;
        $this->isEdit = false;
        $this->emit('refreshDatatable');
    }

    public function reloadBayar()
    {
        $this->transaksibeli = TransaksiBeli::find($this->idNya);
    }

    public function TambahBayar()
    {
        if ($this->transaksibeli->sisa == 0) {
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
        if ($this->bayar > $this->transaksibeli->sisa) {
            throw ValidationException::withMessages([
                'bayar' => 'Bayar Melebihi Hutang...',
            ]);
        }

        try {
            DB::transaction(function () {
                if ($this->transaksibeli->HutangVendor) {
                    $b = HutangVendor::find($this->transaksibeli->HutangVendor->id);
                    $b->update([
                        'bayar' => $b->bayar + $this->bayar,
                        'sisa' => $b->sisa - $this->bayar,
                    ]);
                } else {
                    $b = HutangVendor::create([
                        'vendor_id' => $this->transaksibeli->vendor_id,
                        'transaksi_beli_id' => $this->transaksibeli->id,
                        'awal' => $this->transaksibeli->sisa,
                        'bayar' => $this->bayar,
                        'sisa' => $this->transaksibeli->sisa - $this->bayar,
                    ]);
                }

                $c = HutangVendorDetail::create([
                    'hutang_vendor_id' => $b->id,
                    'bayar' => $this->bayar,
                    'tanggal' => date('Y-m-d'),
                ]);

                $this->transaksibeli->sisa = $this->transaksibeli->sisa - $this->bayar;
                $this->transaksibeli->bayar = $this->transaksibeli->bayar + $this->bayar;
                $this->transaksibeli->save();
            });
            $this->isBayar = false;
            $this->bayar = null;
            $this->reloadBayar();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Pembayaran Berhasil ditambahkan...', // Isi pesan
            ]);
        } catch (\Exception $e) {
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
                $b = HutangVendor::find($this->transaksibeli->HutangVendor->id);
                $b->update([
                    'bayar' => $b->bayar - $this->bayar,
                    'sisa' => $b->sisa + $this->bayar,
                ]);

                HutangVendorDetail::find($this->idBayar)->delete();

                $this->transaksibeli->sisa = $this->transaksibeli->sisa + $this->bayar;
                $this->transaksibeli->bayar = $this->transaksibeli->bayar - $this->bayar;
                $this->transaksibeli->save();
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
        $this->beli = TransaksiBeli::find($id);
        $this->dispatchBrowserEvent('show-detail-modal');
    }

    public function render()
    {
        return view('livewire.admin.pages.hutang.data-hutang-vendor');
    }
}
