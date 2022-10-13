<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtorPropertyHistory extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'pro_detail'];
}
