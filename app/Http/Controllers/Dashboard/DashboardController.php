<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Test;
use Illuminate\Support\Response;


class DashboardController extends Controller
{
    public function index()
    {
        return response(Test::all()->jsonSerialize(), 200);
//        $test = Test::all();
//        return response()->json($test);
    }

    public function patientsList()
    {
        $patients = Patient::with('products')->get();

//        foreach ($patients as $patient) {
//
//            $products = $patient->products();
//            dd($products);
//
//            $patient->products = $products;
//        }
//        return response()->json([
//            'users' => $patients->toArray()
//        ], 200);
        return response()->json($patients);
    }
}
