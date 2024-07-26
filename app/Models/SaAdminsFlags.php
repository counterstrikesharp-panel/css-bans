<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaAdminsFlags extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysql');
    }
    public function permissions() {
        return $this->belongsTo(Permission::class, 'flag', 'permission');
    }
}
