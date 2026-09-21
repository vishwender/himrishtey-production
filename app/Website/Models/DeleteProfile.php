<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeleteProfile extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'delete_profile_request';

    protected $fillable = [
        'user_id',
        'reason',
        'date',
        'status',
    ];

    public $timestamps = false;
}
