<?php

namespace App\Http\Controllers\Diagnoses;

use App\Http\Controllers\Controller;
use App\Models\Diagnos;


class DiagnosesController extends Controller
{
    public function index()
    {
        return response(Diagnos::get());
    }
}
