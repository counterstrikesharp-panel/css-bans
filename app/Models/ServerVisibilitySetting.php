<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerVisibilitySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'is_visible',
    ];

    public function server()
    {
        return $this->belongsTo(SaServer::class, 'server_id');
    }
}
