<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Library\Services\CurrencyRateCalculation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Validator;
use Auth;
use DB;

class TransactionController extends Controller
{   
    public function getLoginUserInfo()
    {
        $userId = Auth::user()->id;
        $userInfo = User::with('wallet')->where('id', $userId)->first();
            
        $response = [
            'success' => true,
            'userInfo' => $userInfo
        ];

        return response()->json($response, 200);
    }

    public function getUserList()
    {
        $userId = Auth::user()->id;
        $userList = User::with('wallet')->where('id', '!=', $userId)->get();
            
        $response = [
            'success' => true,
            'userList' => $userList
        ];

        return response()->json($response, 200);
    }

    public function sendMoney(Request $request, CurrencyRateCalculation $CurrencyRateCalculation)
    {   
        DB::beginTransaction();

        try 
        {
            $validator = Validator::make($request->all(), [
                'receive_user_id' => 'required',
                'amount'  => 'required|integer|min:1'
            ]);

            // validation check for requests
            if($validator->fails())
            {
                return response()->json(['errors' => $validator->errors()]);
            }

            $fromUserInfo = User::with('wallet')->find(Auth::user()->id);
            $toUserInfo = User::with('wallet')->find($request->receive_user_id);

            $currentCurrency = $fromUserInfo->wallet->currency;
            $convertCurrency = $toUserInfo->wallet->currency;
            $sendingAmount = $request->amount;
            
            $convertCurrencyApiResponse = $CurrencyRateCalculation->rateCalculation($currentCurrency, $convertCurrency, $sendingAmount);

            DB::commit();

            $response = [
                'success'    => true,
                'message'   => 'Your money transfer successfully'
            ];
    
            return response()->json($response);

        
        }
        catch (\Exception $e) 
        {
            DB::rollback();
        } 

    }
}
