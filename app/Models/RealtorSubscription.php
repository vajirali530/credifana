<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtorSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subscription_id','used_click','total_click'];
}
