<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demo extends Model
{
    protected $connection = 'demos';

    protected $table = 'dr_demos';

    protected $fillable = [
        'file', 
        'server_name',
        'map',
        'date',
    ];
}