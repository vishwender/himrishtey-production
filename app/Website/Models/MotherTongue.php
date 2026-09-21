<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotherTongue extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'mother_tongues';

    public $timestamps = false;
}
