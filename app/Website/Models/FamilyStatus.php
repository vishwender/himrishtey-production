<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyStatus extends Model
{
    protected $connection = 'site';

    protected $table = 'family_status';

    public $timestamps = false;

    use HasFactory;
}
