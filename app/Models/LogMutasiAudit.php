<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogMutasiAudit extends Model
{
    protected $table = 'log_mutasi_audit';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
