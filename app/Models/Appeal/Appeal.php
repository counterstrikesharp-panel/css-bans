<?php
namespace App\Models\Appeal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'ban_type',
        'steamid',
        'name',
        'reason',
        'email'
    ];
}
