<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtorSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subscription_id', 'plan_name', 'plan_start', 'plan_end', 'is_cancelled', 'used_click','total_click'];
}
