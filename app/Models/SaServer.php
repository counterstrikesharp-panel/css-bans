<?php

namespace App\Models;

use App\Models\Rcon\Rcon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaServer extends Model
{
    use HasFactory;
    public function rcon() {
        return $this->hasOne(Rcon::class, 'server_id', 'id');
    }
}
