<?php

namespace App\Livewire\Admin\Pages\Transaksi;

use App\Models\Agent;
use App\Models\Product;
use App\Models\TransaksiJualPagi as ModelsTransaksiJualPagi;
use App\Models\TransaksiJualPagiDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class TransaksiJualPagi extends Component
{
    public $idNya, $product_id, $product, $harga_beli, $harga_jual, $stock, $qty, $sub_total, $satuan, $list_add_product = [], $jumlah = 0, $total = 0, $agent_id, $agent, $invoice, $jual;
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
            'qty' => 'required|numeric|not_in:0',
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
        $rules['qty'] = 'required|not_in:0';
        $rules['sub_total'] = 'required';
        $this->validate($rules);

        // Check if the product with the same ID already exists in the list
        $existingProductIndex = array_search($this->product_id, array_column($this->list_add_product, 'product_id'));

        if ($existingProductIndex !== false) {
            // If the product already exists, update quantity, harga_beli, and sub_total
            $this->list_add_product[$existingProductIndex]['qty'] += $this->qty;
            $this->total = $this->total - $this->list_add_product[$existingProductIndex]['sub_total'];
            $this->list_add_product[$existingProductIndex]['sub_total'] = $this->list_add_product[$existingProductIndex]['qty'] * $this->harga_jual;
            $this->total += $this->list_add_product[$existingProductIndex]['sub_total'];
        } else {
            // If the product doesn't exist, add it to the list
            $maxId = count($this->list_add_product) > 0 ? max(array_column($this->list_add_product, 'id')) : 0;
            $newProduct = [
                'id' => $maxId + 1,
                'product_id' => $this->product_id,
                'name' => $this->product->name,
                'harga_beli' => $this->harga_beli,
                'harga_jual' => $this->harga_jual,
                'qty' => $this->qty,
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

    public function Transaksi()
    {
        $rules['list_add_product'] = 'required';
        $rules['agent_id'] = 'required';
        $rules['total'] = 'required';
        $this->validate($rules);
        try {
            DB::transaction(function () {
                $this->invoice = time();
                $b = ModelsTransaksiJualPagi::create([
                    'agent_id' => $this->agent_id,
                    'invoice' => $this->invoice,
                    'tanggal' => date('Y-m-d'),
                    'total' => $this->total,
                ]);

                foreach ($this->list_add_product as $v) {
                    $a['transaksi_jual_pagi_id'] = $b->id;
                    $a['product_id'] = $v['product_id'];
                    $a['harga_beli'] = $v['harga_beli'];
                    $a['harga_jual'] = $v['harga_jual'];
                    $a['qty'] = $v['qty'];
                    $a['sub_total'] = $v['sub_total'];
                    TransaksiJualPagiDetail::create($a);
                    $c = Product::find($v['product_id']);
                    $c->update([
                        'qty' => $c->qty - $v['qty'],
                        'total' => ($c->qty - $v['qty']) * $c->harga_jual,
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
                $jual = ModelsTransaksiJualPagi::find($this->idNya);
                foreach ($jual->detailTransaksiJual as $d) {
                    $c = Product::find($d->product_id);
                    $c->update([
                        'qty' =>  $c->qty + $d->qty,
                        'total' => ($c->qty + $d->qty) * $c->harga_jual,
                    ]);
                    TransaksiJualPagiDetail::find($d->id)->delete();
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
        $this->dispatchBrowserEvent('close-select-agent-modal');
    }

    public function pilihProduct()
    {
        $this->dispatchBrowserEvent('show-select-user-modal');
    }

    public function SelectProduct($id)
    {
        $this->product_id = $id;
        $this->product = Product::find($id);
        $this->harga_beli = $this->product->harga_beli;
        $this->harga_jual = $this->product->harga_jual;
        $this->stock = $this->product->qty;
        $this->satuan = $this->product->satuan;
        $this->dispatchBrowserEvent('close-select-user-modal');
    }

    public function Detail($id)
    {
        $this->jual = ModelsTransaksiJualPagi::find($id);
        $this->dispatchBrowserEvent('show-detail-modal');
    }

    public function render()
    {
        return view('livewire.admin.pages.transaksi.transaksi-jual-pagi');
    }
}
