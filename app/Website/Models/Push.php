<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
    protected $connection = 'site';

    protected $table = 'push_subscriptions';

    public $timestamps = false;

    use HasFactory;
}
