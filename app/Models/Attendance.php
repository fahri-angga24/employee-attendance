<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public $table = 'employee_attendance';
    protected $fillable = [
        'user_id',
        'timestamp_attendance',
        'image',
        'location',
        'latitude',
        'longitude'
    ];
}
