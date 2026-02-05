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

    public function data_loan_mob()
    {
        return $this->belongsTo(DataLoanMob::class, 'cif', 'cif');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'unit', 'kode_branch');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kode_kel', 'code_kel');
    }

    public function ao()
    {
        return $this->belongsTo(Ao::class, 'cao', 'cao');
    }
}
