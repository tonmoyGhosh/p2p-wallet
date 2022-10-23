<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatsReportController extends Controller
{
    public function statsReport()
    {
        return view('stats-report');
    }
}
