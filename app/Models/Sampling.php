<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sampling extends Model
{
    protected $table = 'sampling';
    protected $primaryKey = 'cif';
    public $timestamps = false;

    protected $fillable = ['cif', 'kode_status'];
}
