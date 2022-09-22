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

        $allowed_property_types = ['Multi-Family', 'Townhome', 'Single Family', 'Condo'];

        if(!in_array($request->property_type, $allowed_property_types)){
            return response()->json([
                'status' => 'error',
                'message' => "property type not match with our criteria."
            ]);
        }

        if($request->property_type == 'Multi-Family'){
            $request->property_type = 'Apartment';
        }else if($request->property_type == 'Townhome'){
            $request->property_type = 'Townhouse';
        }else if($request->property_type == 'Single Family'){
            $request->property_type = 'Single Family';
        }else if($request->property_type == 'Condo'){
            $request->property_type = 'Condo';
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "property type does not match with our criteria."
            ]);
        }

        if (!isset($request->property_address) || $request->property_address == '') {
            return response()->json([
                'status' => 'error',
                'message' => "address not found."
            ]);
        }        
        if (!isset($request->bedrooms) || $request->bedrooms == '' || $request->bedrooms <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => "please enter valid bedroom."
            ]);
        }

        if (!isset($request->property_price) || $request->property_price == '') {
            return response()->json([
                'status' => 'error',
                'message' => "Property price not found."
            ]);
        }
        
        if (!isset($request->interest_rate) || $request->interest_rate == '' || $request->interest_rate <= 0 || $request->interest_rate > 100) {
            return response()->json([
                'status' => 'error',
                'message' => "Please enter valid interest rate."
            ]);
        }

        if (!isset($request->unit) || $request->unit == '' || $request->unit <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => "Please enter valid unit for property."
            ]);
        }

        if (!isset($request->closing_cost_percent) || $request->closing_cost_percent == '' || $request->closing_cost_percent <= 0 || $request->closing_cost_percent > 100) {
            return response()->json([
                'status' => 'error',
                'message' => "Please enter valid closing cost rate."
            ]);
        }

        if (!isset($request->vacancy) || $request->vacancy == '' || $request->vacancy <= 0 || $request->vacancy > 100) {
            return response()->json([
                'status' => 'error',
                'message' => "Please enter valid vacancy rate."
            ]);
        }

        if (!isset($request->maintenance) || $request->maintenance == '' || $request->maintenance <= 0 || $request->maintenance > 100) {
            return response()->json([
                'status' => 'error',
                'message' => "Please enter valid maintenance rate."
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
                    if($subscriptions->used_click < $subscriptions->total_click){

                        // Rapid API Call to get Rent on specific Area
                        $curl = curl_init();
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
                        $reserr = $response;
                        $err = curl_error($curl);
                        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        curl_close($curl);
                        if ($err) {
                            return response()->json([
                                'status' => 'error',
                                'message' => $err
                            ],400);
                        } else {
                            $response = json_decode($response,true);
                            if(isset($response['rent']) && $response['rent'] != ''){
                                $rentFromApi = $response['rent'];
                                $rentRangeLow = $response['rentRangeLow'];
                                $rentRangeHigh = $response['rentRangeHigh'];
                                $city = $response['listings'][0]['city'] ?? '-';
                                $state = $response['listings'][0]['state'] ?? '-';

                                $property_price = $request->property_price; // from extnsn
                                $downpayment_percent = $request->downpayment_percent ?? 20; // from extnsn
                                $downpayment_payment = ($property_price * $downpayment_percent) / 100;
                                $mortgage = $property_price - $downpayment_payment;
                                                        
                                $closing_cost_percent = $request->closing_cost_percent; // from extnsn
                                $closing_cost_amount = ($property_price * $closing_cost_percent) / 100;


                                $estimate_cost_of_repair = (isset($request->estimate_cost_of_repair) && $request->estimate_cost_of_repair != '') ? $request->estimate_cost_of_repair : 0; // from extnsn
                                $total_capital_needed = $downpayment_payment + $closing_cost_amount;

                                $loan_term_years = $request->loan_term_years ?? 30; // from extnsn
                                $interest_rate = $request->interest_rate; // from extnsn

                                //calculate principal and interest
                                $power = $loan_term_years * 12;
                                $rateformula = pow((($interest_rate / 1200) + 1), $power);
                                
                                $principal_and_interest = floor(((($mortgage * ($interest_rate / 1200)) * $rateformula) / ($rateformula - 1)));
                                
                                //Unit for multi-family if another property type then default 1 unit and user can change it
                                $unit = $request->unit;

                                $gross_monthly_income = $rentFromApi * $unit;
                                $gross_yearly_income = floor($gross_monthly_income * 12);

                                $taxes = (isset($request->taxes) && $request->taxes != '') ? $request->taxes : 0;
                                $insurance = (isset($request->insurance) && $request->insurance != '') ? $request->insurance : 0;
                                $vacancy = ($property_price * $request->vacancy) / 100;   
                                $maintenance = ($property_price * $request->maintenance) / 100;

                                $totalMonthlyCost = $taxes + $insurance + $vacancy + $maintenance;
                                $totalYearlyCost = $totalMonthlyCost * 12;

                                $monthlyNetOperator = $gross_monthly_income - $totalMonthlyCost;
                                $yearlyNetOperator = $monthlyNetOperator * 12;

                                $cap_rate = floor(($yearlyNetOperator / $property_price) * 100);

                                $total_cash_flow_monthly = $monthlyNetOperator - $principal_and_interest;
                                $total_cash_flow_yearly = $total_cash_flow_monthly * 12;

                                $cash_on_cash_return = floor(($total_cash_flow_yearly / ($closing_cost_amount + $downpayment_payment)) * 100);


                                RealtorSubscription::where('user_id',$request->user_id)->update(['used_click' => ($subscriptions->used_click + 1)]);

                                $dataToSend = "
                                                <table width='60%'>
                                                    <tr>
                                                        <td>Property Price</td> <td>".$property_price."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>City | State</td> <td>".$city." | ".$state."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Average Rent</td> <td>".$rentFromApi."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Rent Range Low</td> <td>".$rentRangeLow."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Rent Range High</td> <td>".$rentRangeHigh."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Down Payment (".$downpayment_percent.")%</td> <td>".$downpayment_payment."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mortgage</td> <td>".$mortgage."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Closing Cost (".$closing_cost_percent.")%</td> <td>".$closing_cost_amount."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Estimate cost of repair</td> <td>".$estimate_cost_of_repair."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total capital needed</td> <td>".$total_capital_needed."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Loan Term</td> <td>".$loan_term_years." Year</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Interest rate</td> <td>".$interest_rate."%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Principal and Interest</td> <td>".$principal_and_interest."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Unit</td> <td>".$unit."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gross monthly income</td> <td>".$gross_monthly_income."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gross yearly income</td> <td>".$gross_yearly_income."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Taxes</td> <td>".$taxes."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Insurance</td> <td>".$insurance."</td>
                                                    </tr>
                                                    <tr>
                                                         <td>Vacancy (".$request->vacancy.")%</td> <td>".$vacancy."</td>
                                                    </tr>
                                                    <tr>
                                                         <td>Maintenance (".$request->maintenance.")%</td> <td>".$maintenance."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total monthly cost</td> <td>".$totalMonthlyCost."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total yearly cost</td> <td>".$totalYearlyCost."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Monthly net operator</td> <td>".$monthlyNetOperator."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Yearly net operator</td> <td>".$yearlyNetOperator."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cap rate</td> <td>".$cap_rate."%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total cash flow monthly</td> <td>".$total_cash_flow_monthly."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total cash flow yearly</td> <td>".$total_cash_flow_yearly."</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cash on cash return</td> <td>".$cash_on_cash_return."%</td>
                                                    </tr>
                                                </table>";

                                return response()->json([
                                                'status' => 'success',
                                                'message' => '',
                                                'data' => $dataToSend
                                            ], 200);
                            }else{
                                return response()->json([
                                    'status' => 'error',
                                    'message' => $reserr
                                ],400);
                            }
                        }
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

    public function getPropertyDetails2(){
        pre($_POST);
    }

}
