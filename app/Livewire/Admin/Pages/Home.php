<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Agent;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Operasional;
use App\Models\Product;
use App\Models\TransaksiBeli;
use App\Models\TransaksiJualSore;
use App\Models\TransaksiJualSoreDetail;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    public $karyawan, $product, $agent, $vendor, $pengeluaran, $pemasukan, $dailyData, $monthlyData;

    public function mount()
    {
        $this->karyawan = Karyawan::all();
        $this->product = Product::all();
        $this->agent = Agent::all();
        $this->vendor = Vendor::all();
        $beli = TransaksiBeli::all();
        $gaji = Gaji::all();
        $operasional = Operasional::all();
        $this->pengeluaran = $beli->sum('total') + $gaji->sum('gaji') + $gaji->sum('bonus') + $operasional->sum('total');
        $this->pemasukan = TransaksiJualSore::all();
        // Mendapatkan tanggal awal dan akhir bulan ini
        $dailyStartDate = Carbon::now()->startOfMonth();
        $dailyEndDate = Carbon::now()->endOfMonth();

        // Mengambil data jumlah transaksi per hari dalam bulan ini
        $dailyData = TransaksiJualSoreDetail::selectRaw('DATE(created_at) as tanggal')
            ->selectRaw('SUM((harga_jual-harga_beli)*qty_keluar) as jumlah_transaksi')
            ->whereBetween('created_at', [$dailyStartDate, $dailyEndDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        foreach ($dailyData as $tgl) {
            $dailyChartData['labels'][] = $tgl->tanggal;
            $dailyChartData['jumlah'][] = (int) $tgl->jumlah_transaksi;
        }

        if (isset($dailyChartData)) {
            $this->dailyData = json_encode($dailyChartData);
        }

        // Mendapatkan tanggal awal dan akhir satu tahun ini
        $monthlyStartDate = Carbon::now()->startOfYear();
        $monthlyEndDate = Carbon::now()->endOfYear();

        // Mengambil data jumlah transaksi per bulan dalam satu tahun
        $monthlyData = TransaksiJualSoreDetail::selectRaw('YEAR(created_at) as tahun')
            ->selectRaw('MONTH(created_at) as bulan')
            ->selectRaw('SUM((harga_jual-harga_beli)*qty_keluar) as jumlah_transaksi')
            ->whereBetween('created_at', [$monthlyStartDate, $monthlyEndDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderByRaw('YEAR(created_at), MONTH(created_at)') // Use orderByRaw and remove DB::raw()
            ->get();

        // Initialize an array to store chart data
        $monthlyChartData = [];

        foreach ($monthlyData as $bulan) {
            $monthlyChartData['labels'][] = $bulan->bulan . '/' . $bulan->tahun; // Format: MM/YYYY
            $monthlyChartData['jumlah'][] = (int) $bulan->jumlah_transaksi;
        }

        // Convert the array to JSON
        if (!empty($monthlyChartData)) {
            $this->monthlyData = json_encode($monthlyChartData);
            // Pass $monthlyData to your view or wherever needed
        }
    }

    public function render()
    {

        return view('livewire.admin.pages.home');
    }
}
