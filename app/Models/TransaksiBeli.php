<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class TransaksiBeli extends Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function HutangVendor()
    {
        return $this->belongsTo(HutangVendor::class, 'id', 'transaksi_beli_id');
    }

    public function detailTransaksiBeli()
    {
        return $this->hasMany(DetailTransaksiBeli::class, 'transaksi_beli_id');
    }
}
