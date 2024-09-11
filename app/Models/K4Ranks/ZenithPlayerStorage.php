<?php

namespace App\Models\K4Ranks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZenithPlayerStorage extends Model
{
    protected $table = 'zenith_player_storage';

    protected $casts = [
        'K4-Zenith-Ranks.storage' => 'array',
        'K4-Zenith-TimeStats.storage' => 'array',
        'K4-Zenith-Stats.storage' => 'array',
    ];

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysqlranks');
    }
}
