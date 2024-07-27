<?php

namespace App\Models\VIP;

use Illuminate\Database\Eloquent\Model;

class VIPServer extends Model
{
    public $timestamps = false;
    protected $table = 'vip_servers';
    protected $primaryKey = 'serverId';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysqlvip');
    }
}
