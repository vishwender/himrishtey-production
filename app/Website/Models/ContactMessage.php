<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $connection = 'site';

    use HasFactory;

    public const SUBJECTS = [
        'Profile or account help', 'Membership or payment', 'Report a concern',
        'Matchmaking assistance', 'Feedback or suggestion', 'Other inquiry',
    ];

    protected $fillable = [
        'site_key', 'name', 'email', 'phone', 'profile_id', 'subject',
        'message', 'ip_address', 'user_agent',
    ];
}
