<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysql');
    }

    public function admin() {
        return $this->hasMany(SaAdmin::class, 'flags', 'permission');
    }
}
