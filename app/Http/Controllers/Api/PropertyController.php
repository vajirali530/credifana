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
        if (!isset($request->property_type) || $request->property_type == '') {
            return response()->json([
                'status' => 'error',
                'message' => "property type not found."
            ]);
        }
        if (!isset($request->property_address) || $request->property_address == '') {
            return response()->json([
                'status' => 'error',
                'message' => "address not found."
            ]);
        }
        if (!isset($request->bedrooms) || $request->bedrooms == '') {
            return response()->json([
                'status' => 'error',
                'message' => "bedrooms not found."
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
                    // Rapid API Call to get Rent on specific Area

                    /*$curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://realty-mole-property-api.p.rapidapi.com/rentalPrice?address=".$request->property_address."&propertyType=".$request->property_type."&bedrooms=".$request->bedrooms."&compCount=2",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => [
                            "X-RapidAPI-Host: ".env("X_RAPIDAPI_HOST"),
                            "X-RapidAPI-Key: ".env("X_RAPIDAPI_KEY")
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
                        print_r($response);
                    }*/
                    if($subscriptions->used_click < $subscriptions->total_click){

                        $property_price = 599900; // from extnsn
                        $downpayment_percent = $request->downpayment_percent ?? 20; // from extnsn
                        $downpayment_payment = ($property_price * $downpayment_percent) / 100;
                        $mortgage = $property_price - $downpayment_payment;

                        $closing_cost_percent = 4; // from extnsn
                        $closing_cost_amount = ($property_price * $closing_cost_percent) / 100;

                        $estimate_cost_of_repair = 2; // from extnsn
                        $total_capital_needed = $downpayment_payment + $closing_cost_amount;

                        $loan_term_years = 30; // from extnsn
                        $interest_rate = 5; // from extnsn
                        $principal_and_interest = 12800; // from extnsn

                        // pre($total_capital_needed);
                        $response = [
                                    "rent" => 1061.73,
                                    "rentRangeLow" => 1020.91,
                                    "rentRangeHigh" => 1102.55,
                                    "longitude" => -119.7339938,
                                    "latitude" => 36.7313029,
                                    "listings" => [
                                        [
                                            "id" => "1151-S-Chestnut-Ave,-Unit-137,-Fresno,-CA-93702",
                                            "formattedAddress" => "1151 S Chestnut Ave, Unit 137, Fresno, CA 93702",
                                            "longitude" => -119.733612,
                                            "latitude" => 36.73113,
                                            "city" => "Fresno",
                                            "state" => "CA",
                                            "zipcode" => "93702",
                                            "price" => 1095,
                                            "publishedDate" => "2021-09-23T01:34:05.282Z",
                                            "distance" => 0.039124493329239805,
                                            "daysOld" => 359.17,
                                            "correlation" => 0.9969,
                                            "address" => "1151 S Chestnut Ave, Unit 137",
                                            "county" => "Fresno County",
                                            "bedrooms" => 2,
                                            "bathrooms" => 2,
                                            "propertyType" => "Condo",
                                            "squareFootage" => 1013
                                        ]
                                    ]
                                ];

                        RealtorSubscription::where('user_id',$request->user_id)->update(['used_click' => ($subscriptions->used_click + 1)]);

                        return response()->json([
                                        'status' => 'success',
                                        'message' => '',
                                        'data' => $response
                                    ], 200);
                    }else{
                        return response()->json([
                                    'status' => 'error',
                                    'message' => "You have reached your maximum limit. please upgrade your plan on credifana. <a href='".route('pricing')."'' target='_blank'>Click Here</a>"
                                ],400);
                    }
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
