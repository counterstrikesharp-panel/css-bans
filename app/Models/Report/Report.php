<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'ban_type',
        'steamid',
        'ip',
        'nickname',
        'comments',
        'name',
        'email',
        'server_id',
        'media_link',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysql');
    }

    public function server()
    {
        return $this->belongsTo(\App\Models\SaServer::class);
    }
}
