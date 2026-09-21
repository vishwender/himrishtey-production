<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletOffer extends Model
{
    protected $connection = 'site';

    protected $table = 'wallet_offers';

    public $timestamps = false;

    use HasFactory;
}
