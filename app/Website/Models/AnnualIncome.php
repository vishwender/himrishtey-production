<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualIncome extends Model
{
    protected $connection = 'site';

    protected $table = 'annual_incomes';

    public $timestamps = false;

    use HasFactory;
}
