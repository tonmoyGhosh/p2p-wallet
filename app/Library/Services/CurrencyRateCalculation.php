<?php
namespace App\Library\Services;

use Illuminate\Support\Facades\Http;
   
class CurrencyRateCalculation
{   
    public function rateCalculation($currentCurrency, $convertCurrency, $sendingAmount)
    {   
        if($currentCurrency && $convertCurrency && $sendingAmount)
        {
            // set api key to access api
            $apiKey = '8iMkeYBYHtEsebIuKvTuIc9LEMHN2fX1';

            // convert currency amount with current rate
            $response = Http::withHeaders([
                            'apikey' => $apiKey
                        ])
                        ->accept('application/json')
                        ->get('https://api.apilayer.com/fixer/convert', [
                            'from'   => $currentCurrency,
                            'to'     => $convertCurrency,
                            'amount' => $sendingAmount
                        ]);

            return json_decode($response);
        }
        else
        {
            $response = [
                'success'    => false,
                'message'   => 'This request is not valid'
            ];

            return $response;
        }
    }
}