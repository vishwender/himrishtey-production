<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberWallet extends Model
{
    protected $connection = 'site';

    protected $table = 'member_wallet';

    use HasFactory;

    protected $fillable = [];

    protected $guarded = [];

    public $timestamps = false;
}
