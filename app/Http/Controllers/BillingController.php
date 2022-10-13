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
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));

        $subscription_id = 'sub_1LpsipEviaLTUto6Nk6syaTm';

        $newSubscriptionData = $stripe->subscriptions->retrieve(
                                $subscription_id,
                                []
                            );
        pre($newSubscriptionData);
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

                $plan_name = getPlanName($newSubscriptionData->plan->id);
                $total_click = getTotalClicks($plan_name);

                $subscriptionData = RealtorSubscription::where('user_id',$user->id)->first();
                
                if($subscriptionData->subscription_id != ''){

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
                }

                $subscriptionSaveData = [
                                        'subscription_id' => $subscription_id,
                                        'plan_name' => $plan_name,
                                        'plan_start' => date('Y-m-d H:i:s', $newSubscriptionData->current_period_start),
                                        'plan_end' => date('Y-m-d H:i:s', $newSubscriptionData->current_period_end),
                                        'is_cancelled' => 0,
                                        'used_click' => 0,
                                        'total_click' => $total_click,
                                        ];

                RealtorSubscription::where('user_id',$user->id)->update($subscriptionSaveData);
                    
                                
                // $payment_history_data = [
                //                     'user_id' => $user->id,
                //                     'subscription_id' => $subscription_id,
                //                     'amount' => $amount
                //                 ];
                // RealtorPaymentHistory::insert($payment_history_data);
            }


            if($eventType == 'customer.subscription.updated'){
                
                $subscription_id = $payload->data->object->id;
                $amount = $payload->data->object->plan->amount / 100;
                $plan_id = $payload->data->object->plan->id;
                $status = $payload->data->object->status;
                $plan_start = $payload->data->object->current_period_start;
                $plan_end = $payload->data->object->current_period_end;
               
                if($status == 'active'){
                
                    $plan_name = getPlanName($plan_id);
                    $total_click = getTotalClicks($plan_name);

                    $subscriptionSaveData = [
                                            'plan_name' => $plan_name,
                                            'plan_start' => date('Y-m-d H:i:s', $plan_start),
                                            'plan_end' => date('Y-m-d H:i:s', $plan_end),
                                            'is_cancelled' => 0,
                                            'used_click' => 0, 
                                            'total_click' => $total_click
                                        ];
                    $subscriptionData = RealtorSubscription::where('subscription_id',$subscription_id)->first();
                    if($subscriptionData){
                        $result = $subscriptionData->update($subscriptionSaveData);
                        
                        $payment_history_data = [
                                        'user_id' => $subscriptionData->user_id,
                                        'subscription_id' => $subscription_id,
                                        'amount' => $amount
                                    ];
                        RealtorPaymentHistory::insert($payment_history_data);
                    }
                }else{
                    require base_path().'/vendor/autoload.php';
                    $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));


                    // cancel existing subscriptions
                    $subscription = $stripe->subscriptions->cancel(
                                    $subscription_id,
                                    []
                                );
                    RealtorSubscription::where('subscription_id',$subscription_id)->update(['is_cancelled' => 1]);
                }
            }

         } catch (Exception $e) {
            plog("ERROR => ".print_r($e->getMessage(), true));
            //throw $th;
        }
    }

    public function cronEvent(Request $request){

        require base_path().'/vendor/autoload.php';
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));

        try{
            $subsData = RealtorSubscription::where('plan_end','<',date('Y-m-d'))
                                            ->get()
                                            ->toArray();
            
            foreach($subsData as $detail){
                if($detail['subscription_id'] != ''){

                    $existingSubscription = $stripe->subscriptions->retrieve(
                        $detail['subscription_id'],
                        []
                    );
                    
                    if($existingSubscription && $existingSubscription->status == 'active'){
                        // cancel existing subscriptions
                        $subscription = $stripe->subscriptions->cancel(
                                            $detail['subscription_id'],
                                            []
                                        );
                    }
                }

                $subscriptionSaveData = [
                                        'subscription_id' => null,
                                        'plan_name' => 'basic',
                                        'plan_start' => date('Y-m-d H:i:s'),
                                        'plan_end' => date('Y-m-d H:i:s', strtotime("+30 days")),
                                        'is_cancelled' => 0,
                                        'used_click' => 0,
                                        'total_click' => getTotalClicks('basic')
                                        ];
            
                RealtorSubscription::where('id', $detail['id'])->update($subscriptionSaveData);
            }
        } catch (Exception $e) {
            // print_r($e->getMessage());
            plog("ERROR => ".print_r($e->getMessage(), true));
        }
    }

}
