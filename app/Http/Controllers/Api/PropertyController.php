<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\RealtorSubscription;

class PropertyController extends Controller
{
    public function getPropertyDetails(Request $request) {
        
        if (!isset($request->user_id) || $request->user_id == '') {
            return response()->json([
                'status' => 'error',
                'message' => "user not exist."
            ]);
        }

        try {

            //check user exist or not
            $user = User::where('id',$request->user_id)->first();
            if($user == null){
                return response()->json([
                    'status' => 'error',
                    'message' => "user not found."
                ],400);
            }

            //check user subscribed or not
            $subscriptions = RealtorSubscription::where('user_id',$request->user_id)->first();
            if($subscriptions == null){
                
                return response()->json([
                    'status' => 'error',
                    'message' => "please subscribed on credifana. <a href='".route('pricing')."' target='_blank'>Click Here</a>"
                ],400);
            }else{
                //first check subscription status Active or not?
                require base_path().'/vendor/autoload.php';
                \Stripe\Stripe::setApiKey(env("STRIPE_SECRET_KEY"));
                 

                $subscription = \Stripe\Subscription::retrieve(
                                    $subscriptions->subscription_id,
                                    []
                                );
                if($subscription && $subscription['status'] == 'active'){
                    // Rapid API Call

                    /*$curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://realty-mole-property-api.p.rapidapi.com/properties?address=5500%20Grand%20Lake%20Dr%2C%20San%20Antonio%2C%20TX%2C%2078244",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => [
                            "X-RapidAPI-Host: realty-mole-property-api.p.rapidapi.com",
                            "X-RapidAPI-Key: 3d86123917msh425ff53a8072c4ap1988fbjsnbed64ad0328b"
                        ],
                    ]);
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    if ($err) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $err
                        ],400);
                    } else {
                        echo $response;
                    }*/

                    $property_data['tax'] = 1200; 
                    $property_data['investment'] = 1300; 
                    $property_data['months'] = 12; 
                    $property_data['installment'] = 13; 
                    $property_data['service_tax'] = 1200; 
                    $property_data['extra_tax'] = 1200;
                    
                    return response()->json([
                                        'status' => 'success',
                                        'message' => '',
                                        'data' => $property_data
                                    ], 200);
                }else{
                    return response()->json([
                                    'status' => 'error',
                                    'message' => "Your Subscription has been expired or cancelled. please subscribed on credifana. <a href='".route('pricing')."'' target='_blank'>Click Here</a>"
                                ],400);
                }
            }

        } catch (\Throwable $th) {
            
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);

        }
    }

}
