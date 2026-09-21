<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortlist extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'short_listed';   // your custom table

    protected $guarded = [];

    public $timestamps = false;

    // protected $hidden = ['password', 'remember_token'];
}
