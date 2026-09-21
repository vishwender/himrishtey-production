<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileViewed extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'profile_viewed';

    public $timestamps = false;
}
