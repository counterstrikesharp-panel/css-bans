<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaGroupsServers extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function groups() {
        return $this->belongsTo(SaGroups::class, 'group_id', 'id');
    }

    public function groupsFlags() {
        return $this->belongsTo(SaGroupsFlags::class, 'group_id', 'group_id');
    }
}
