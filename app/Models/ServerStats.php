<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerStats extends Model
{
    use HasFactory;

    public $table = 'server_player_stats';

    public $timestamps = false;

    protected $fillable = ['server_id', 'player_count', 'map', 'recorded_at'];

    public function server()
    {
        return $this->belongsTo(SaServer::class, 'server_id');
    }
}
