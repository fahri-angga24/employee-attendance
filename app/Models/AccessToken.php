<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    use HasFactory;

    public $table = 'access_token';
    protected $fillable = [
        'user_id',
        'access_token',
        'expired_at'
    ];
}
