<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RealtorSubscription;
use App\Models\RealtorPaymentHistory;
use Illuminate\Contracts\Encryption\DecryptException;
use Exception;

class BillingController extends Controller{
    
    public function index(Request $request){
        try {
            $data = [];
            if(isset($request->token) && $request->token != ''){
                $data['email'] = decrypt($request->token);
            }
            return view('pages.billing',$data);

        } catch (Exception $e) {
            return redirect()->route('pricing')->with('error','User does not found.');
        }
    }

    public function billingCheckout(Request $request){

        if(!isset($request->email) ||  $request->email == ''){
            return redirect()->route('pricing')->with('error','User does not found.');
        }

        require base_path().'/vendor/autoload.php';

        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET_KEY"));

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $session = \Stripe\Checkout\Session::create([
            'customer_email' => $request->email,
            'payment_method_types' => ['card'],
            'line_items' => [[
            'price' => $request->selectedPlan,
            'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('thankyou'),
            'cancel_url' => route('pricing'),
        ]);

        header('location:'.$session->url);
        exit;
    }


    public function webhookEvent(Request $request){

        $payload = json_decode($request->getContent());
        $eventType = $payload->type;
        // $email = $payload['data']['customer_email'];

        $logFile = fopen("stripe_log.txt", "a") or die("Unable to open file!");
        fwrite($logFile, date('d-m-Y H:i:s').print_r($payload, true)."\n");
        // fwrite($logFile, date('d-m-Y H:i:s')." Event=> ".$eventType." | Payload => ".$request->getContent()." \n");
        fclose($logFile);
        
        exit;
       /* if($eventType == 'customer.subscription.created'){
            $payment_history_data = [
                                'user_id' => $user->id,
                                'subscription_id' => $subscription_id,
                                'amount' => $amount
                            ];
            RealtorPaymentHistory::insert($payment_history_data);
        }


        $email = 'ali@gmail.com';
        $subscription_id = 'fsdfsdfsdfds';
        $amount = 120;

        $user = User::where('email',$email)->first();
        if($user == null){
            echo "user not found";
            exit;
        }*/
        
        

        
    }
}
