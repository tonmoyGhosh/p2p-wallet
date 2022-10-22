<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required'
        ]);

        // validation check for requests
        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        // login attempt with request values
        else if(!Auth::attempt($request->all()))
        {   
            $response = [
                'success' => false,
                'message'  => 'Invalid login credintials'
            ];

            return response()->json($response, 200);
        }
        // generate access token to api login
        else
        {   
            $accessToken = Auth::user()->createToken('accessToken')->accessToken;

            $response = [
                'success' => true,
                'user' => Auth::user(),
                'apiToken'  => $accessToken
            ];
    
            return response()->json($response, 200);
        }
    }   
}
