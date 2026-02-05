<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RencanaAudit extends Model
{
    protected $table = 'rencana_audit';

    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'unit', 'kode_branch');
    }
}
