<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tescrud extends Model
{
    protected $table = 'tes';
    protected $fillable = ['tes_kol','tes_kol2'];
}