<?php

namespace App\Models\VIP;

use Illuminate\Database\Eloquent\Model;

class VIP extends Model
{
    protected $table = 'vip_users';
    protected $primaryKey = 'account_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'name',
        'lastvisit',
        'sid',
        'group',
        'expires'
    ];

    protected $casts = [
        'account_id' => 'integer',
        'lastvisit' => 'integer',
        'sid' => 'integer',
        'expires' => 'integer'
    ];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysqlvip');
    }
    public function server()
    {
        return $this->belongsTo(VIPServer::class, 'sid', 'serverId');
    }
}
