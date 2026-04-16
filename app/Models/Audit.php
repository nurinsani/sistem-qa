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
}
