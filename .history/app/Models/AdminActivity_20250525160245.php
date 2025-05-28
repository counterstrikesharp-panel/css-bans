<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'affected_user_id',
        'action_type', // e.g. 'ban', 'unban', 'mute', 'unmute'
        'description',
        'ip_address',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function affectedUser()
    {
        return $this->belongsTo(User::class, 'affected_user_id');
    }
}
