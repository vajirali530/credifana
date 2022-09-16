<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
                            'status' => 'pwd_error',
                            'message' => 'Password does not match'
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'status' => 'unauthorized',
                        'message' => 'Invalid Credentials'
                    ], 401);
                }

            } catch (\Throwable $th) {
                
                return response()->json([
                    'error' => true,
                    'message' => $th->getMessage()
                ]);

            }
        } else {
            return response()->json([
                'status' => 'error',
                'errors' => $login_data->errors()
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

            return response()->json([
                'status' => 'success',
                'user_data' => $registeredUser
            ]);

        } catch (\Throwable $th) {
            
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ]);

        }
    }
}