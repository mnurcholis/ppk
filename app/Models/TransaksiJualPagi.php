<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class TransaksiJualPagi extends Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function detailTransaksiJual()
    {
        return $this->hasMany(TransaksiJualPagiDetail::class, 'transaksi_jual_pagi_id');
    }
}
