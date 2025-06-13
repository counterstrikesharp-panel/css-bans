<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'admin_name',
        'admin_steam_id',
        'action',
        'target_id',
        'target_type',
        'target_name',
        'target_steam_id',
        'details',
        'ip_address'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
