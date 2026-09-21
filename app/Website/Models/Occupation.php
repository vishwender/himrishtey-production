<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $fillable = ['occuption', 'status'];

    public $timestamps = false;
}
