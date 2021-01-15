<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Patient;

use Illuminate\Support\Response;


class DashboardController extends Controller
{
    public function index()
    {
        return response(Test::all()->jsonSerialize(), 200);
    }

    public function patientsList()
    {
        $patients = Patient::with('products')->get();
        return response()->json(
            $patients
        );
    }
}
