<?php

namespace App\Livewire\Admin\Pages\Transaksi;

use App\Models\Agent;
use App\Models\Product;
use App\Models\TransaksiJualPagi;
use App\Models\TransaksiJualPagiDetail;
use App\Models\TransaksiJualSore as ModelsTransaksiJualSore;
use App\Models\TransaksiJualSoreDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class TransaksiJualSore extends Component
{
    public $idNya, $product_id, $product, $harga_beli, $harga_jual, $stock, $qty, $sub_total, $satuan, $list_add_product = [], $jumlah = 0, $total = 0, $agent_id, $agent, $invoice, $invoice_pagi, $jual, $bayar, $sisa;
    public $isEdit = false;

    protected $listeners = ['SelectProduct', 'SelectAgent', 'CetakInvoice', 'ConfirmBatal', 'Detail'];

    public function add()
    {
        $this->isEdit = !$this->isEdit;
    }

    public function cancel()
    {
        $this->isEdit = !$this->isEdit;
        $this->idNya = null;
    }

    public function updatedQty()
    {
        $rules = [
            'qty' => 'required|numeric',
        ];

        // Validasi apakah stock kurang dari atau sama dengan qty
        if ($this->qty > $this->stock) {
            throw ValidationException::withMessages([
                'qty' => 'Qty Sudah Maksimal Barang yang tersedia.',
            ]);
        }

        $this->validate($rules);

        // Jika validasi berhasil, hitung sub_total
        $this->sub_total = $this->qty * $this->harga_jual;
    }

    public function tambahProduct()
    {
        $rules['product_id'] = 'required';
        $rules['harga_beli'] = 'required';
        $rules['harga_jual'] = 'required';
        $rules['qty'] = 'required';
        $rules['sub_total'] = 'required';
        $this->validate($rules);

        // Check if the product with the same ID already exists in the list
        $existingProductIndex = array_search($this->product_id, array_column($this->list_add_product, 'product_id'));

        if ($existingProductIndex !== false) {
            if (($this->list_add_product[$existingProductIndex]['qty_keluar'] + $this->qty) > $this->stock) {
                throw ValidationException::withMessages([
                    'qty' => 'Qty Sudah Maksimal Barang yang tersedia.',
                ]);
            }
            // If the product already exists, update quantity, harga_beli, and sub_total
            $this->list_add_product[$existingProductIndex]['qty_keluar'] += $this->qty;
            $this->list_add_product[$existingProductIndex]['qty_sisa'] -= $this->qty;
            $this->total = $this->total - $this->list_add_product[$existingProductIndex]['sub_total'];
            $this->list_add_product[$existingProductIndex]['sub_total'] = $this->list_add_product[$existingProductIndex]['qty_keluar'] * $this->harga_jual;
            $this->total += $this->list_add_product[$existingProductIndex]['sub_total'];
        } else {
            // If the product doesn't exist, add it to the list
            $maxId = count($this->list_add_product) > 0 ? max(array_column($this->list_add_product, 'id')) : 0;
            $qty_asal = $this->stock;
            $qty_keluar = $this->qty;
            $qty_sisa = $qty_asal - $qty_keluar;
            $newProduct = [
                'id' => $maxId + 1,
                'product_id' => $this->product_id,
                'name' => $this->product->product->name,
                'harga_beli' => $this->harga_beli,
                'harga_jual' => $this->harga_jual,
                'qty_asal' => $qty_asal,
                'qty_keluar' => $qty_keluar,
                'qty_sisa' => $qty_sisa,
                'sub_total' => $this->qty * $this->harga_jual,
            ];
            $this->list_add_product[] = $newProduct;
            $this->total += $this->sub_total;
        }

        // Reset properties
        $this->product_id = null;
        $this->product = null;
        $this->harga_beli = null;
        $this->harga_jual = null;
        $this->qty = null;
        $this->sub_total = null;
        $this->satuan = null;
    }

    public function hapusProduct($id, $kurang)
    {
        $this->total = $this->total - $kurang;
        $this->list_add_product = array_filter($this->list_add_product, function ($item) use ($id) {
            return $item['id'] !== $id;
        });
    }

    public function updatedBayar()
    {
        if ($this->bayar == '') {
            $this->sisa = $this->total;
        } else {
            $this->sisa = $this->total - $this->bayar;
        }
    }

    public function updatedTotal()
    {
        if ($this->bayar == '') {
            $this->sisa = $this->total;
        } else {
            $this->sisa = $this->total - $this->bayar;
        }
    }

    public function Transaksi()
    {
        $rules['agent_id'] = 'required';
        $rules['list_add_product'] = 'required';
        $rules['total'] = 'required';
        $rules['bayar'] = 'required';
        $rules['sisa'] = 'required';

        $this->validate($rules);
        if (count($this->list_add_product) != TransaksiJualPagiDetail::where('transaksi_jual_pagi_id', $this->invoice_pagi)->count()) {
            throw ValidationException::withMessages([
                'list_add_product' => 'Jumlah Item Belum sama Transaksi Pagi......',
            ]);
        }
        if ($this->agent->status == 'STATUS_AGENT_02' and $this->sisa != 0) {
            throw ValidationException::withMessages([
                'agent_id' => 'Agent Tidak Tetap tidak boleh Hutang...',
            ]);
        }
        try {
            DB::transaction(function () {
                $this->invoice = time();
                $b = ModelsTransaksiJualSore::create([
                    'agent_id' => $this->agent_id,
                    'transaksi_jual_pagi_id' => $this->invoice_pagi,
                    'invoice' => $this->invoice,
                    'tanggal' => date('Y-m-d'),
                    'total' => $this->total,
                    'bayar' => $this->bayar,
                    'sisa' => $this->sisa,
                ]);

                foreach ($this->list_add_product as $v) {
                    $a['transaksi_jual_sore_id'] = $b->id;
                    $a['product_id'] = $v['product_id'];
                    $a['harga_beli'] = $v['harga_beli'];
                    $a['harga_jual'] = $v['harga_jual'];
                    $a['qty_asal'] = $v['qty_asal'];
                    $a['qty_keluar'] = $v['qty_keluar'];
                    $a['qty_sisa'] = $v['qty_sisa'];
                    $a['sub_total'] = $v['sub_total'];
                    TransaksiJualSoreDetail::create($a);
                    $c = Product::find($v['product_id']);
                    $c->update([
                        'qty' => $c->qty + $v['qty_sisa'],
                        'total' => ($c->qty + $v['qty_sisa']) * $c->harga_jual,
                    ]);
                }
            });
            $this->emit('refreshDatatable');
            $this->isEdit =  false;
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Transaksi Berhasil...', // Isi pesan
            ]);
            $this->list_add_product = [];
            $this->agent_id = null;
            $this->total = null;
            $this->dispatchBrowserEvent('show-print-modal');
        } catch (\Exception $e) {
            dd($e);
            $this->emit('refreshDatatable');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Gaji Tidak dapat dihapus...', // Isi pesan
            ]);
        }
    }

    public function CetakInvoice($invoice)
    {
        $this->emit('cetakInvoice', $invoice);
        // $this->dispatchBrowserEvent('close-print-modal');
    }

    public function CetakResi()
    {
        $this->emit('cetakInvoice', $this->invoice);
        $this->dispatchBrowserEvent('close-print-modal');
    }

    public function ConfirmBatal($id)
    {
        $this->idNya = $id;
        $this->dispatchBrowserEvent('show-batal-beli-modal');
    }

    public function CancelBatal()
    {
        $this->idNya = null;
        $this->dispatchBrowserEvent('close-batal-beli-modal');
    }

    public function Batal()
    {
        try {
            DB::transaction(function () {
                $jual = ModelsTransaksiJualSore::find($this->idNya);
                foreach ($jual->detailTransaksiJual as $d) {
                    $c = Product::find($d->product_id);
                    $c->update([
                        'qty' =>  $c->qty - $d->qty_sisa,
                        'total' => ($c->qty - $d->qty_sisa) * $c->harga_jual,
                    ]);
                    TransaksiJualSoreDetail::find($d->id)->delete();
                }
                $jual->delete();
            });
            $this->emit('refreshDatatable');
            $this->idNya = '';
            $this->dispatchBrowserEvent('close-batal-beli-modal');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Transaksi Jual Pagi Berhasil Dibatal...', // Isi pesan
            ]);
        } catch (\Exception $e) {
            $this->emit('refreshDatatable');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Transaksi Jual Pagi Gagal Dibatal...', // Isi pesan
            ]);
        }
    }

    public function pilihAgent()
    {
        $this->dispatchBrowserEvent('show-select-agent-modal');
    }

    public function SelectAgent($id)
    {
        $this->agent_id = $id;
        $this->agent = Agent::find($id);
        $this->invoice_pagi = TransaksiJualPagi::where('agent_id', $this->agent_id)
            ->where('tanggal', date('Y-m-d'))
            ->first(); // Menggunakan first() untuk mendapatkan satu baris hasil

        $this->dispatchBrowserEvent('close-select-agent-modal');
        if ($this->invoice_pagi) {
            $this->invoice_pagi = $this->invoice_pagi->id;
        } else {
            // Handle jika tidak ada hasil ditemukan
            $this->invoice_pagi = null;
            $this->agent_id = null;
            $this->agent = null;
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Agent yang anda pilih tidak memiliki transaksi pagi ini...', // Isi pesan
            ]);
        }

        $this->list_add_product = [];
        $this->total = null;
        $this->bayar = null;
        $this->sisa = null;
    }

    public function pilihProduct()
    {
        $rules['agent_id'] = 'required';
        $this->validate($rules);
        $this->emit('refreshDatatable');
        $this->dispatchBrowserEvent('show-jual-pagi-modal');
    }

    public function SelectProduct($id)
    {
        $this->product = TransaksiJualPagiDetail::find($id);
        $this->product_id = $this->product->product->id;
        $this->harga_beli = $this->product->harga_beli;
        $this->harga_jual = $this->product->harga_jual;
        $this->stock = $this->product->qty;
        $this->satuan = $this->product->product->satuan;
        $this->dispatchBrowserEvent('close-jual-pagi-modal');
    }

    public function Detail($id)
    {
        $this->jual = ModelsTransaksiJualSore::find($id);
        $this->dispatchBrowserEvent('show-detail-modal');
    }

    public function render()
    {
        return view('livewire.admin.pages.transaksi.transaksi-jual-sore');
    }
}
