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
use Carbon\Carbon;

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
            $sendingAmount = ($request->amount) ? $request->amount : '';

            if($fromUserInfo && $toUserInfo && $sendingAmount)
            {
                if($fromUserInfo->wallet->amount < $sendingAmount)
                {
                    $response = [
                        'success'    => false,
                        'message'   => 'Your do not have efficient balance to send money, check your wallet balance'
                    ];
    
                    return response()->json($response);
                }
    
                $currentCurrency = $fromUserInfo->wallet->currency;
                $convertCurrency =  $toUserInfo->wallet->currency;
                
                $convertCurrencyApiResponse = $CurrencyRateCalculation->rateCalculation($currentCurrency, $convertCurrency, $sendingAmount);

                // store transaction data in db
                if($convertCurrencyApiResponse->success)
                {   
                    $transaction = new Transaction;
                    $transaction->send_user_id = $fromUserInfo->id;
                    $transaction->receive_user_id = $toUserInfo->id;
                    $transaction->sending_amount = $sendingAmount;
                    $transaction->current_rate = $convertCurrencyApiResponse->info->rate;
                    $transaction->convert_amount = round($convertCurrencyApiResponse->result, 2);
                    $transaction->transaction_date = Carbon::today()->toDateString();
                    $transaction->status = 'Success';
                    $transaction->save();
    
                
                    $wallet = Wallet::where('user_id', $fromUserInfo->id)->first();
                    $wallet->amount = $wallet->amount - $sendingAmount;
                    $wallet->update();
                    
                    $transactionStatus = true;
                }
                // API error exception handle
                else
                {       
                    $transaction = new Transaction;
                    $transaction->send_user_id = $fromUserInfo->id;
                    $transaction->receive_user_id = $toUserInfo->id;
                    $transaction->sending_amount = 0;
                    $transaction->current_rate = 0;
                    $transaction->convert_amount = 0;
                    $transaction->transaction_date = Carbon::today()->toDateString();
                    $transaction->status = 'Failed';
                    $transaction->log = $convertCurrencyApiResponse->error->info;
                    $transaction->save();

                    $transactionStatus = false;
                }
                
                DB::commit();
    
                $response = [
                    'success'    => $transactionStatus,
                    'message'   => ($transactionStatus) ? 'Your money transfer successfully' : 'This is request is not valid'
                ];
        
                return response()->json($response);
            }
            else
            {
                $response = [
                    'success'    => false,
                    'message'   => 'This is request is not valid'
                ];
        
                return response()->json($response);
            }

        }
        catch (\Exception $e) 
        {
            DB::rollback();
        } 

    }

    public function statsReport()
    {   
        // most conversion data
        $mostUsedConversion = User::join('transactions', 'transactions.send_user_id', 'users.id')
                                    ->select('users.id', 'users.name', 'users.email', 
                                    DB::raw('round(SUM(transactions.convert_amount)) as total_convert_amount'))
                                    ->where('transactions.status', 'Success')
                                    ->groupBy('users.id')
                                    ->orderBy('total_convert_amount', 'DESC')      
                                    ->limit(1)
                                    ->first();

        // user wise total converted amount data
        $userTotalCovertAmount = User::join('transactions', 'transactions.send_user_id', 'users.id')
                                        ->select('users.id', 'users.name', 'users.email', 
                                        DB::raw('round(SUM(transactions.convert_amount)) as total_convert_amount'))
                                        ->where('transactions.status', 'Success')
                                        ->groupBy('users.id')
                                        ->orderBy('total_convert_amount', 'DESC')
                                        ->get();

        // user wise third highest amount data
        $userThirdHighestAmount = DB::select(DB::raw("SELECT   send_user_id as user_id, (
                                        SELECT   sending_amount
                                        FROM     transactions t2
                                        WHERE    t2.send_user_id = t1.send_user_id
                                        ORDER BY sending_amount DESC
                                        LIMIT    2, 1
                                    ) as third_highest_amount, t3.name as user_name, t3.email as user_email
                                    FROM  transactions t1
                                    JOIN users as t3 ON t3.id = t1.send_user_id
                                    GROUP BY send_user_id"));

        $response = [
            'success'                => true,
            'mostUsedConversion'     => ($mostUsedConversion) ? $mostUsedConversion : null,
            'userTotalCovertAmount'  => ($userTotalCovertAmount) ? $userTotalCovertAmount : null,
            'userThirdHighestAmount' => ($userThirdHighestAmount) ? $userThirdHighestAmount : null
            
        ];

        return response()->json($response);
    }
}
