<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ao extends Model
{
    protected $table = 'ao';
    protected $primaryKey = 'cao';
    public $incrementing = false;
    protected $keyType = 'string';
}
