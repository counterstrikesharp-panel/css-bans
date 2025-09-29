<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'steam_id',
        'avatar',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'steam_id' => 'string'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('mysql');
    }
    public function permissions()
    {
        return $this->hasManyThrough(
            SaAdminsFlags::class,
            SaAdmin::class,
            'player_steamid',
            'admin_id',
            'steam_id',
            'id'
        );
    }

    public function servers()
    {
        return $this->hasMany(SaAdmin::class, 'player_steamid', 'steam_id');
    }

    public function groupPermissions() {
        return $this->hasManyThrough(
            SaGroupsFlags::class,
            SaAdmin::class,
            'player_steamid',
            'group_id',
            'steam_id',
            'group_id'
        );
    }
}
