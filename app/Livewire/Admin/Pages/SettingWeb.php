<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingWeb extends Component
{
    use WithFileUploads;

    public $title, $slogan, $deskripsi_situs, $logo, $edit_logo, $favicon, $edit_favicon;

    public function mount()
    {
        $data = Setting::find(1);
        $this->title = $data->title;
        $this->slogan = $data->slogan;
        $this->deskripsi_situs = $data->deskripsi_situs;
        $this->edit_logo = $data->logo;
        $this->edit_favicon = $data->favicon;
    }

    public function simpan()
    {
        $setting = Setting::find(1);
        $setting->title = $this->title;
        $setting->slogan = $this->slogan;
        $setting->deskripsi_situs = $this->deskripsi_situs;
        if ($this->logo != '') {
            if ($this->edit_logo != "") {
                if (Storage::exists($this->edit_logo)) {
                    Storage::delete($this->edit_logo);
                }
            }
            $setting->logo = $this->logo->store('public/setting_web');
        }
        if ($this->favicon != '') {
            if ($this->edit_favicon != "") {
                if (Storage::exists($this->edit_favicon)) {
                    Storage::delete($this->edit_favicon);
                }
            }
            $setting->favicon = $this->favicon->store('public/setting_web');
        }
        $setting->save();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success', // Jenis alert, misalnya 'success', 'error', atau 'warning
            'text' => 'Berhasil disimpan...', // Isi pesan
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pages.setting-web');
    }
}
