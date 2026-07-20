<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $table = 'kelompok';
    protected $primaryKey = 'code_kel';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_kel',
        'code_unit',
        'nama_kel',
        'alamat',
        'cao',
        'cif',
        'tlp',
    ];
}
