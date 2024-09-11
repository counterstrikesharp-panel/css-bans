<?php

namespace App\Models\K4Ranks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZenithMapStats extends Model
{
    use HasFactory;

    public $table = 'zenith_map_stats';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysqlranks');
    }
}
