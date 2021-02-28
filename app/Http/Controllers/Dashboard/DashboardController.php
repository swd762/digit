<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Patient;


class DashboardController extends Controller
{
    public function index()
    {
        return response(Test::all()->jsonSerialize(), 200);
    }

    /**
     * список пациентов для кабинета
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function patientsList()
    {
        $patients = Patient::get();
        return response()->json(
            $patients
        );
    }

    /**
     * Информация о пациенте
     *
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
     */
    public function patientInfo(Patient $patient)
    {
        $patient->load(['diagnoses' => function ($q) {
            $q->wherePivot('active', 1);
        }]);

        return response()->json($patient);
    }
}
