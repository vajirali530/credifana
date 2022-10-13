<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RealtorSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    public function login(Request $request) {
        $login_data = Validator::make($request->all(), [
            'email' => 'required|email:filter',
            'password' => 'required'
        ]);

        if (!$login_data->fails()) {
            try {
                $validated = $login_data->validated();
                $user_data = User::where('email', $validated['email'])->first();

                if ($user_data && $user_data->count() > 0) {
                    if (Hash::check($validated['password'], $user_data->password)) {
                        return response()->json([
                            'status' => 'success',
                            'user_data' => $user_data
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Invalid Credentials'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid Credentials'
                    ]);
                }

            } catch (\Throwable $th) {
                
                return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage()
                ]);

            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'please enter valid data'
            ]);
        }

    }

    public function register(Request $request) {
        $reg_data = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email:filter|unique:App\Models\User,email',
            'password' => 'required'
        ]);

        if ($reg_data->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $reg_data->errors()
            ]);
        }
        try {
            $user_data = $reg_data->validated();
            $user_data['password'] = Hash::make($user_data['password']);

            $registeredUser = User::create($user_data);
            
            //start basic plan for this user
            $subscriptionSaveData = [
                                    'user_id' => $registeredUser->id,
                                    'subscription_id' => null,
                                    'plan_name' => 'basic',
                                    'plan_start' => date('Y-m-d H:i:s'),
                                    'plan_end' => date('Y-m-d H:i:s', strtotime("+30 days")),
                                    'used_click' => 0,
                                    'total_click' => getTotalClicks('basic'),
                                    ];

            RealtorSubscription::insert($subscriptionSaveData);
            
            return response()->json([
                'status' => 'success',
                'user_data' => $registeredUser
            ]);

        } catch (\Throwable $th) {
            
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);

        }
    }
}