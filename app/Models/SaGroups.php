<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaGroups extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysql');
    }
    public function groupFlags() {
        return $this->hasMany(SaGroupsFlags::class, 'group_id', 'id');
    }
    public function groupServers() {
        return $this->hasMany(SaGroupsServers::class, 'group_id', 'id');
    }
}
