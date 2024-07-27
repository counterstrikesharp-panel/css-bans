<?php

namespace App\Models\K4Ranks;

use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    protected $table = 'k4stats';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysqlranks');
    }
}
