<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaAdmin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        // SteamID64 is a 17-digit number that loses precision once serialized
        // to JSON and parsed as a JS number. Keep it a string so /list/admins
        // renders correct Steam profile links. See #164 (and #163 for users).
        'player_steamid' => 'string',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysql');
    }
    public function servers()
    {
        return $this->belongsTo(SaServer::class, 'server_id', 'id');
    }

    public function adminFlags() {
        return $this->hasMany(SaAdminsFlags::class, 'admin_id', 'id');
    }

    public function adminGroups() {
        return $this->hasMany(SaGroupsServers::class, 'group_id', 'group_id');
    }

    public function groupsServers()
    {
        return $this->hasMany(SaGroupsServers::class, 'server_id', 'server_id');
    }

}
