<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCc extends Model
{
    use HasFactory;

    protected $table = 'user_cc';

    protected $fillable = [
        'user_id',
        'name',
        'apr',
        'cashback'
    ];

    protected $hidden = [
        'user_id'
    ];
}
