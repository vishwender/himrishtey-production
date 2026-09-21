<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPhotos extends Model
{
    protected $connection = 'site';

    public $timestamps = false;

    use HasFactory;

    protected $table = 'member_photos';

    protected $fillable = [
        'member_id',
        'photo',
        'photo_approved',
        'photo_privacy',
    ];
}
