<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SendMoneyController extends Controller
{
    public function sendMoney()
    {   
       return view('send-money');
    }
}
