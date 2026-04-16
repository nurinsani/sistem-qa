<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataLoanMob extends Model
{
    protected $table = 'data_loan_mob';
    protected $primaryKey = 'cif';
    public $incrementing = false;

    protected $guarded = [];
}
