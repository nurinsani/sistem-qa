<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audit';

    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(AuditDetail::class, 'cif', 'cif');
    }

    public function dataSampling()
    {
        return $this->belongsTo(DataSampling::class, 'cif', 'cif');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kode_kel', 'code_kel');
    }

    public function ao()
    {
        return $this->belongsTo(Ao::class, 'cao', 'cao');
    }

    public function qa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
