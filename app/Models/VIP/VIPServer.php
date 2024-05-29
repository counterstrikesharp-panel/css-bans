<?php

namespace App\Models\VIP;

use Illuminate\Database\Eloquent\Model;

class VIPServer extends Model
{
    public $timestamps = false;
    protected $table = 'vip_servers';
    protected $primaryKey = 'serverId';
}
