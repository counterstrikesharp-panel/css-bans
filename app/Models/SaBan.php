<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaBan extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function server()
    {
        return $this->belongsTo(SaServer::class, 'server_id', 'id');
    }
}
