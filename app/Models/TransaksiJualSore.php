<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class TransaksiJualSore extends Model implements Auditable
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
        return $this->hasMany(TransaksiJualSoreDetail::class, 'transaksi_jual_sore_id');
    }

    public function HutangAgent()
    {
        return $this->belongsTo(HutangAgent::class, 'id', 'transaksi_jual_sore_id');
    }
}
