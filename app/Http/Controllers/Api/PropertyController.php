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
        $reg_data = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($reg_data->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $reg_data->errors()
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
                    'message' => "please subscribed on credifana. <a href='".route('pricing')."'>Click Here</a>"
                ],400);
            }


        } catch (\Throwable $th) {
            
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ]);

        }
        
        return response()->json([
                            'status' => 'pwd_error',
                            'message' => 'Password does not match'
                        ], 400);

    }


    public function getPropertyDetails2(Request $request) {
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
    }
}
