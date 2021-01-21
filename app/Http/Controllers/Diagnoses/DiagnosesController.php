<?php

namespace App\Http\Controllers\Diagnoses;

use App\Http\Controllers\Controller;
use App\Models\Diagnos;
use App\Models\Patient;
use Illuminate\Http\Request;

class DiagnosesController extends Controller
{
    /**
     * Возвращает список диагнозов
     * Если передан id пациента - исключается уже привязанные диагнозы из списка
     *
     * @param Request $request
     * @var Int patientId
     *
     * @return json
     */
    public function index(Request $request)
    {
        $query = Diagnos::query();
        if ($request->patientId) {
            $patient = Patient::find($request->patientId);
            $attachedDiagnosIds = $patient->diagnoses()->wherePivot('active', 1)->select('diagnoses.id')->pluck('diagnoses.id')->all();
            $query->whereNotIn('id', $attachedDiagnosIds);
        }

        return response()->json($query->get());
    }
}
