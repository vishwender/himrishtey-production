<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $connection = 'site';

    protected $table = 'educations';

    use HasFactory;

    public $timestamps = false;
}
