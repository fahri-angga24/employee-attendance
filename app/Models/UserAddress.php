<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    public $table = 'user_address';
    protected $fillable = [
        'user_id',
        'full_address',
        'province',
        'city',
        'district',
        'zipcode'
    ];
}
