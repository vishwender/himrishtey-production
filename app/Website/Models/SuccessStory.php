<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'success_stories';

    protected $fillable = [
        'groom_name',
        'bride_name',
        'photo',
        'detail',
        'user_id',
    ];

    public $timestamps = false;
}
