<?php

use App\Http\Controllers\CetakInvoiceController;
use App\Http\Controllers\HelperController;
use App\Livewire\Admin\Pages\Agent\DataAgent;
use App\Livewire\Admin\Pages\Permission;
use App\Livewire\Admin\Pages\Role;
use App\Livewire\Admin\Pages\User;
use App\Livewire\Admin\Pages\Home;
use App\Livewire\Admin\Pages\Hutang\DataHutangAgent;
use App\Livewire\Admin\Pages\Hutang\DataHutangVendor;
use App\Livewire\Admin\Pages\Karyawan\DataKaryawan;
use App\Livewire\Admin\Pages\Karyawan\GajiKaryawan;
use App\Livewire\Admin\Pages\Laporan\Laporan;
use App\Livewire\Admin\Pages\Operasional\DataOpersional;
use App\Livewire\Admin\Pages\Product\DataProduct;
use App\Livewire\Admin\Pages\SettingWeb;
use App\Livewire\Admin\Pages\Transaksi\TransaksiBeli;
use App\Livewire\Admin\Pages\Transaksi\TransaksiJualPagi;
use App\Livewire\Admin\Pages\Transaksi\TransaksiJualSore;
use App\Livewire\Admin\Pages\Vendor\DataVendor;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('template', function () {
    return File::get(public_path() . '/documentation.html');
});


Route::get('/', function () {
    return view('welcome');
});
Route::get('show-picture}', [HelperController::class, 'showPicture'])->name('helper.show-picture');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', Home::class)->name('home');
    Route::get('invoice-print/{id}', [CetakInvoiceController::class, 'printInvoiceBeli']);
    Route::get('invoice-pagi-print/{id}', [CetakInvoiceController::class, 'printInvoicePagi']);
    Route::get('invoice-sore-print/{id}', [CetakInvoiceController::class, 'printInvoiceSore']);
    Route::get('/karyawan', DataKaryawan::class)->name('karyawan');
    Route::get('/agent', DataAgent::class)->name('agent');
    Route::get('/vendor', DataVendor::class)->name('vendor');
    Route::get('/beli', TransaksiBeli::class)->name('beli');
    Route::get('/jualpagi', TransaksiJualPagi::class)->name('jualpagi');
    Route::get('/jualsore', TransaksiJualSore::class)->name('jualsore');
    Route::get('/operasional', DataOpersional::class)->name('operasional');
    Route::get('/gaji', GajiKaryawan::class)->name('gaji');
    Route::get('/dataproduct', DataProduct::class)->name('dataproduct');
    // Hutang
    Route::get('/datahutangvendor', DataHutangVendor::class)->name('datahutangvendor');
    Route::get('/datahutangagent', DataHutangAgent::class)->name('datahutangagent');
    // LAPORAN
    Route::get('/laporan', Laporan::class)->name('laporan');
    Route::get('/user', User::class)->name('user');
    Route::get('/role', Role::class)->name('role');
    Route::get('/permission', Permission::class)->name('permission');
    Route::get('/setting', SettingWeb::class)->name('setting');
});
