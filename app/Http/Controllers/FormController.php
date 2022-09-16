<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Mail\ThankYouEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FormController extends Controller
{
    public function index(Request $request) {
        $captcha = $request->input('recaptcha_response');

        if (!$this->verifyCaptchaScore($captcha)) {
            return response()->json([
                'toast' => 'Google Captcha validation failed'
            ], 409);
        }

        $validated = $request->validate([
            'user_email' => 'required|email:filter',
            'user_name' => 'required',
            'user_phone' => 'required',
            'user_requirement' => 'required',
        ]);

        $validated['country_name'] = $request->input('country-name');
        $validated['dial_code'] = $request->input('dial-code');

        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ContactEmail($validated));
        Mail::to($validated['user_email'])->send(new ThankYouEmail());

        return response()->json([
            'success'=> 'Form submited successfully'
        ], 200);
    }

    public function verifyCaptchaScore($recaptcha_response){
        // Make and decode POST request:
        $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . env('GOOGLE_CAPTCHA_SECRET_KEY') . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);

        if (isset($recaptcha->score) && $recaptcha->score >= env('GOOGLE_CAPTCHA_SCORE_LIMIT')) {
            return true;
        }else{
            return false;
        }
    }
}
