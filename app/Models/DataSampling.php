<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSampling extends Model
{
    protected $table = 'data_sampling';

    protected $fillable = [
        'unit',
        'cif',
        'id_ref_sampling',
        'nama',
        'kode_kel',
        'cao',
        'jenis_audit',
        'user_id',
        'status_sampling',
    ];
}
