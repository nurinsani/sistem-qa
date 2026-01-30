<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branch';
    protected $primaryKey = 'kode_branch';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'kode_branch',
        'unit',
        'code_area',
        'area',
        'code_region',
        'region',
        'alamat',
        'GL',
        'tgl_open',
        'status_aktif',
        'status_approve',
    ];
}
