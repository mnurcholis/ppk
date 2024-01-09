<?php

namespace App\Livewire\Admin\Pages\Transaksi;

use App\Models\DetailTransaksiBeli;
use App\Models\Product;
use App\Models\TransaksiBeli as ModelsTransaksiBeli;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransaksiBeli extends Component
{
    public $idNya, $product_id, $product, $harga_beli, $qty, $sub_total, $satuan, $list_add_product = [], $jumlah = 0, $total = 0, $vendor_id, $vendor, $bayar = 0, $sisa, $invoice, $beli;
    public $isEdit = false;

    protected $listeners = ['SelectProduct', 'SelectVendor', 'CetakInvoiceBeli', 'ConfirmBatalBeli', 'DetailBeli'];

    public function add()
    {
        $this->isEdit = !$this->isEdit;
    }

    public function cancel()
    {
        $this->isEdit = !$this->isEdit;
        $this->idNya = null;
    }

    public function updatedHargaBeli()
    {
        $this->sub_total = $this->qty * $this->harga_beli;
    }

    public function updatedQty()
    {
        $this->sub_total = $this->qty * $this->harga_beli;
    }

    public function tambahProduct()
    {
        $rules['product_id'] = 'required';
        $rules['harga_beli'] = 'required';
        $rules['qty'] = 'required';
        $rules['sub_total'] = 'required';
        $this->validate($rules);

        // Check if the product with the same ID already exists in the list
        $existingProductIndex = array_search($this->product_id, array_column($this->list_add_product, 'product_id'));

        if ($existingProductIndex !== false) {
            // If the product already exists, update quantity, harga_beli, and sub_total
            $this->list_add_product[$existingProductIndex]['qty'] += $this->qty;
            $this->list_add_product[$existingProductIndex]['harga_beli'] = $this->harga_beli;
            $this->total = $this->total - $this->list_add_product[$existingProductIndex]['sub_total'];
            $this->list_add_product[$existingProductIndex]['sub_total'] = $this->list_add_product[$existingProductIndex]['qty'] * $this->harga_beli;
            $this->total += $this->list_add_product[$existingProductIndex]['sub_total'];
        } else {
            // If the product doesn't exist, add it to the list
            $maxId = count($this->list_add_product) > 0 ? max(array_column($this->list_add_product, 'id')) : 0;
            $newProduct = [
                'id' => $maxId + 1,
                'product_id' => $this->product_id,
                'name' => $this->product->name,
                'harga_beli' => $this->harga_beli,
                'qty' => $this->qty,
                'sub_total' => $this->qty * $this->harga_beli,
            ];
            $this->list_add_product[] = $newProduct;
            $this->total += $this->sub_total;
        }

        // Reset properties
        $this->product_id = null;
        $this->product = null;
        $this->harga_beli = null;
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
        $rules['list_add_product'] = 'required';
        $rules['vendor_id'] = 'required';
        $rules['bayar'] = 'required';
        $rules['total'] = 'required';
        $rules['sisa'] = 'required';
        $this->validate($rules);
        try {
            DB::transaction(function () {
                $this->invoice = time();
                $b = ModelsTransaksiBeli::create([
                    'vendor_id' => $this->vendor_id,
                    'invoice' => $this->invoice,
                    'tanggal' => date('Y-m-d'),
                    'total' => $this->total,
                    'bayar' => $this->bayar,
                    'sisa' => $this->sisa,
                ]);

                foreach ($this->list_add_product as $v) {
                    $a['transaksi_beli_id'] = $b->id;
                    $a['product_id'] = $v['product_id'];
                    $a['harga_beli'] = $v['harga_beli'];
                    $a['qty'] = $v['qty'];
                    $a['sub_total'] = $v['sub_total'];
                    DetailTransaksiBeli::create($a);
                    $c = Product::find($v['product_id']);
                    $c->update([
                        'harga_beli' => $v['harga_beli'],
                        'qty' => $v['qty'] + $c->qty,
                        'total' => ($v['qty'] + $c->qty) * $c->harga_jual,
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
            $this->vendor_id = null;
            $this->bayar = null;
            $this->total = null;
            $this->sisa = null;
            $this->dispatchBrowserEvent('show-print-modal');
        } catch (\Exception $e) {
            $this->emit('refreshDatatable');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Gaji Tidak dapat dihapus...', // Isi pesan
            ]);
        }
    }

    public function CetakInvoiceBeli($invoice)
    {
        $this->emit('cetakInvoice', $invoice);
        // $this->dispatchBrowserEvent('close-print-modal');
    }

    public function CetakResi()
    {
        $this->emit('cetakInvoice', $this->invoice);
        // $this->dispatchBrowserEvent('close-print-modal');
    }

    public function ConfirmBatalBeli($id)
    {
        $this->idNya = $id;
        $this->dispatchBrowserEvent('show-batal-beli-modal');
    }

    public function CancelBatalBeli()
    {
        $this->idNya = null;
        $this->dispatchBrowserEvent('close-batal-beli-modal');
    }

    public function BatalBeli()
    {
        try {
            DB::transaction(function () {
                $beli = ModelsTransaksiBeli::find($this->idNya);
                foreach ($beli->detailTransaksiBeli as $d) {
                    $c = Product::find($d->product_id);
                    $c->update([
                        'qty' =>  $c->qty - $d->qty,
                        'total' => ($c->qty - $d->qty) * $c->harga_jual,
                    ]);
                    DetailTransaksiBeli::find($d->id)->delete();
                }
                $beli->delete();
            });
            $this->emit('refreshDatatable');
            $this->idNya = '';
            $this->dispatchBrowserEvent('close-batal-beli-modal');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Transaksi Beli Berhasil Dibatal...', // Isi pesan
            ]);
        } catch (\Exception $e) {
            $this->emit('refreshDatatable');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error', // Jenis alert, misalnya 'success', 'error', atau 'warning
                'text' => 'Transaksi Beli Gagal Dibatal...', // Isi pesan
            ]);
        }
    }

    public function pilihVendor()
    {
        $this->dispatchBrowserEvent('show-select-vendor-modal');
    }

    public function SelectVendor($id)
    {
        $this->vendor_id = $id;
        $this->vendor = Vendor::find($id);
        $this->dispatchBrowserEvent('close-select-vendor-modal');
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
        $this->satuan = $this->product->satuan;
        $this->dispatchBrowserEvent('close-select-user-modal');
    }

    public function DetailBeli($id)
    {
        $this->beli = ModelsTransaksiBeli::find($id);
        $this->dispatchBrowserEvent('show-detail-modal');
    }

    public function render()
    {
        return view('livewire.admin.pages.transaksi.transaksi-beli');
    }
}
