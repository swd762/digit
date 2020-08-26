<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Test;
use Illuminate\Support\Response;


class DashboardController extends Controller
{
    public function index() {
        return response(Test::all()->jsonSerialize(),200);
//        $test = Test::all();
//        return response()->json($test);
    }
}
