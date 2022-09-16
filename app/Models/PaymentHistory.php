<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $table = "payment_history";

    protected $fillable = [
        'user_id',
        'subscription_id',
        'amount'
    ];

   
}
