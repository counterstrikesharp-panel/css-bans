<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaAdmin extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function servers()
    {
        return $this->belongsTo(SaServer::class, 'server_id', 'id');
    }

    public function permissions() {
        return $this->belongsTo(Permission::class, 'flags', 'permission');
    }
}
