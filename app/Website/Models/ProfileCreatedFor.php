<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileCreatedFor extends Model
{
    protected $connection = 'site';

    protected $table = 'profile_created_for';

    use HasFactory;
}
