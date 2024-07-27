<?php

namespace App\Models\K4Ranks;

use Illuminate\Database\Eloquent\Model;

class Ranks extends Model
{
    protected $table = 'k4ranks';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysqlranks');
    }

    public function k4stats()
    {
        return $this->hasOne(Stats::class, 'steam_id', 'steam_id');
    }
}
