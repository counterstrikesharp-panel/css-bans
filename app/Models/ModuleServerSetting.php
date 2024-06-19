<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ModuleServerSetting extends Model
{
    use HasFactory;

    protected $table = 'module_server_settings';

    protected $fillable = [
        'module_name',
        'name',
        'db_host',
        'db_user',
        'db_pass',
        'db_name',
        'active',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysql');

    }
    public function setDbPassAttribute($value)
    {
        $this->attributes['db_pass'] = Crypt::encryptString($value);
    }

    public function getDbPassAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
