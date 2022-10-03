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
            'cancel_url' => route('pricing')
        ]);

        header('location:'.$session->url);
        exit;
    }



    public function webhookEvent2(Request $request){
        require base_path().'/vendor/autoload.php';
        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET_KEY"));

        $newSubscriptionData = \Stripe\Subscription::retrieve(
                                'sub_1LonoxEviaLTUto6PYL0o8Ti',
                                []
                            );
        
    }


    public function webhookEvent(Request $request){
        try {
            $payload = json_decode($request->getContent());
            $eventType = $payload->type;
            
            if($eventType == 'checkout.session.completed'){
                    $email = $payload->data->object->customer_email;
                    $subscription_id = $payload->data->object->subscription;
                    $amount = $payload->data->object->amount_total / 100;
                    
                    $user = User::where('email',$email)->first();
                    
                    if($user == null){
                        plog("Error: user (".$email.") not found");
                        exit;
                    }

                    require base_path().'/vendor/autoload.php';
                    $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));

                    $newSubscriptionData = $stripe->subscriptions->retrieve(
                                            $subscription_id,
                                            []
                                        );
                    $total_click = getTotalClicks($newSubscriptionData->plan->id);

                    $subscriptionData = RealtorSubscription::where('user_id',$user->id)->first();
                    
                    if($subscriptionData == null){
                        $subscriptionSaveData = [
                                                'user_id' => $user->id,
                                                'subscription_id' => $subscription_id,
                                                'used_click' => 0,
                                                'total_click' => $total_click,
                                                ];

                        RealtorSubscription::insert($subscriptionSaveData);
                    }else{
                        
                        $existingSubscription = $stripe->subscriptions->retrieve(
                                            $subscriptionData->subscription_id,
                                            []
                                        );
                        if($existingSubscription && $existingSubscription->status == 'active'){
                            
                            // cancel existing subscriptions
                           $subscription = $stripe->subscriptions->cancel(
                                            $subscriptionData->subscription_id,
                                            []
                                        );                            
                        }

                        $subscriptionSaveData = [
                                                'subscription_id' => $subscription_id,
                                                'used_click' => 0,
                                                'total_click' => $total_click,
                                                ];

                        RealtorSubscription::where('user_id',$user->id)->update($subscriptionSaveData);
                    }
                                
                $payment_history_data = [
                                    'user_id' => $user->id,
                                    'subscription_id' => $subscription_id,
                                    'amount' => $amount
                                ];
                RealtorPaymentHistory::insert($payment_history_data);
            }


            if($eventType == 'customer.subscription.updated'){
                $email = $payload->data->object->customer_email;
                $subscription_id = $payload->data->object->subscription;
                $amount = $payload->data->object->amount_total / 100;
                
                $user = User::where('email',$email)->first();
                
                if($user == null){
                    plog("Error: user (".$email.") not found");
                    exit;
                }

                require base_path().'/vendor/autoload.php';
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));

                $subscriptionData = $stripe->subscriptions->retrieve(
                                        $subscription_id,
                                        []
                                    );
                if($subscriptionData && $subscriptionData->status == 'active'){
                    $total_click = getTotalClicks($subscriptionData->plan->id);

                    $subscriptionSaveData = [
                                                'subscription_id' => $subscription_id,
                                                'used_click' => 0,
                                                'total_click' => $total_click,
                                                ];

                    RealtorSubscription::where('user_id',$user->id)->update($subscriptionSaveData);


                    $payment_history_data = [
                                    'user_id' => $user->id,
                                    'subscription_id' => $subscription_id,
                                    'amount' => $amount
                                ];
                    RealtorPaymentHistory::insert($payment_history_data);
                }
            }


         } catch (Exception $e) { 
            plog("ERROR => ".print_r($e->getMessage(), true));
            //throw $th;
        }
    }
}
