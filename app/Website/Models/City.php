<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $connection = 'site';

    use HasFactory;

    public $timestamps = false;

    protected $table = 'cities';
}
