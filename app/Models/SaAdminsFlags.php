<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaAdminsFlags extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function permissions() {
        return $this->belongsTo(Permission::class, 'flag', 'permission');
    }
}
