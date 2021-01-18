<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    public function index()
    {
        return response(Test::all()->jsonSerialize(), 200);
    }

    public function patientsList()
    {
        $patients = Patient::get();
        return response()->json(
            $patients
        );
    }

    public function patientInfo(Patient $patient)
    {
        $patient->load(['diagnoses' => function ($q) {
            $q->wherePivot('active', 1)->withPivot(['comment', 'issue_date']);
        }, 'diagnoses.pivot.product.modules']);

        return response()->json($patient);
    }

    /**
     * Добавляет диагноз пациенту
     *
     * @param Patient $patient
     * @param Request $request
     * @var int $diagnosId - id диагноза для прикрепления
     * @var string $diagnosComment - комментарий врача к поставленному диагнозу
     *
     * @return json
     */
    public function attachDiagnos(Patient $patient, Request $request)
    {
        $patient->diagnoses()->attach($request->diagnosId, [
            'comment' => $request->diagnosComment,
            'issue_date' => Carbon::now()->toDateString()
        ]);

        return response()->json(['status' => 'success', 'msg' => 'Диагноз добавлен']);
    }
}
