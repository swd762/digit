<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    /**
     * Контроллер для работы с данными с модулей
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */

    // модуль для импорта данных с модуля и запись в таблицу
    public function import(Request $request)
    {
        Log::info('module id: ' . $request->id);
        Log::info('data: ' . $request->data);
        Log::info('all: ' . json_encode($request->all()));

        $module = Module::where('module_id', $request->id)->first();
        if ($module == null) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Module not found'
            ], 404);
        }

        $patient = $module->currentPatient()->first();

        $data = json_decode($request->data);

        $temperaturePropName = "Temperature (grad C)";

        foreach ($data as $item) {
            ModuleData::create([
                'patient_id' => $patient->id,
                'module_id' => $module->id,
                'temperature' => $item->$temperaturePropName,
                'bend' => $item->Bend,
                'created_at' => $item->Date
            ]);
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
