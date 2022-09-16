<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CcDetails;
use App\Models\UserCc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExtensionController extends Controller
{
    public function getCCDetails($user_id=null) 
    {
        try {
            
            $cc_details = CcDetails::all()->toArray();
            $user_cc_details = UserCc::where('user_id',$user_id)->get()->toArray();
    
            $merge_details = array_merge($cc_details, $user_cc_details);
            
            return response()->json([
                'success' => true,
                'data' => $merge_details,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'result' => $th->getMessage()
            ]);
        }
    }

    public function setCustomCard(Request $request)
    {
        $card_data = Validator::make($request->all(), [
            'user_id' => 'required',
            'customcard_name' => 'required',
            'cc_apr' => 'required',
            'cc_cashback' => 'required',
        ]);

        if ($card_data->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $card_data->errors()
            ]);
        }

        try {
            $validated = $card_data->validated();
            $cc_data = UserCc::updateOrCreate(
                ['user_id' => $validated['user_id']],
                [
                    'name' => $validated['customcard_name'], 
                    'apr' => $validated['cc_apr'], 
                    'cashback' => $validated['cc_cashback']
                ]
            );

            return response()->json([
                'success' => true,
                'result' => 'Card successfully added.',
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'result' => $th->getMessage()
            ]);
        }

    }
}
