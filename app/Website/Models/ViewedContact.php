<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewedContact extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'viewed_contacts';

    public $timestamps = false;
}
