<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaAdmin extends Model
{
    use HasFactory;

    public $timestamps = false;

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
