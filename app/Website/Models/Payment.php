<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'site';

    use HasFactory;

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
