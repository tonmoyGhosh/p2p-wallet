<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LogoutController extends Controller
{
    public function logout()
    {   
        // revoked access token for api
        $logoutResponse = Auth::user()->token()->revoke();

        if($logoutResponse == 1)
        {
            $response = [
                'success' => true,
                'message' => 'User logout successfully'
            ];
        }
        else
        {
            $response = [
                'success' => false,
                'message' => 'something went wrong, try again'
            ];
        }

        return response()->json($response, 200);
    }
}
