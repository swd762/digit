<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleData;
use App\Models\PatientDiagnosPivot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Контроллер для работы с историей пациента
 */
class LogsController extends Controller
{
    public function index(Request $request)
    {
        $data = PatientDiagnosPivot::where('patient_id', $request->patientId)->with(['product', 'module', 'diagnos'])->get();


        return response()->json($data);
    }
}
