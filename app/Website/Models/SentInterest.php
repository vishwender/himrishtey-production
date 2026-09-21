<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentInterest extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'sent_interests';

    public $timestamps = false;
}
