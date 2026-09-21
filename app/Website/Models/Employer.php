<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $fillabel = ['employer'];

    public $timestamps = false;
}
