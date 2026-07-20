<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tanggapan extends Model
{
    protected $table = 'tanggapan';

    protected $guarded = [];

    public function audit()
    {
        return $this->belongsTo(Audit::class, 'id_audit');
    }
}
