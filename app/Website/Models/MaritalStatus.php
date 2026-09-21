<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'marital_status';

    public $timestamps = false;
}
