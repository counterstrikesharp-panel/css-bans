<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaMute extends Model
{
    public $timestamps = false;

    use HasFactory;

    public function server()
    {
        return $this->belongsTo(SaServer::class, 'server_id', 'id');
    }
}
