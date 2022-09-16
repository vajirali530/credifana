<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\PaymentHistory;

class BillingController extends Controller{
    
    public function billingCheckout(Request $request){
        require base_path().'/vendor/autoload.php';

        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET_KEY"));

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
            'price' => $request->selectedPlan,
            'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('thankyou'),
            'cancel_url' => route('pricing'),
        ]);

        echo  json_encode([ 'id' => $session->id ]);
        exit;
    }


    public function webhookEvent(){
        $email = 'ali@gmail.com';
        $subscription_id = 'fsdfsdfsdfds';
        $amount = 120;

        $user = User::where('email',$email)->first();
        if($user == null){
            echo "user not found";
            exit;
        }
        
        $payment_history_data = [
                                'user_id' => $user->id,
                                'subscription_id' => $subscription_id,
                                'amount' => $amount
                            ];
        PaymentHistory::insert($payment_history_data);

        
    }
}
