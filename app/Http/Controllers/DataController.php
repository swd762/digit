<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Контроллер для работы с данными с устройств сбора и передачи данных (УСПД)
 */
class DataController extends Controller
{
    /**
     * Метод для сохранения данных, полученных с устройств сбора и передачи данных (УСПД).
     * Входные параметры:
     *  id УСПД
     *  данные в json формате
     *
     * Возвращает результат операции в json формате
     *
     * @param Request $request
     * @var String id - id УСПД
     * @var String data - данные с УСПД в json формате
     *
     * @return Json
     */
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
                'patient_id' => $patient ? $patient->id : null,
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


    /**
     * Метод для получения данных, собранных с УСПД
     *
     * @param Request $request
     * @var Int moduleId - id УСПд
     * @var String dateFrom - дата начала выборки
     * @var String dateTo - дата окончания выборки
     *
     * @return json
     */
    public function getData(Request $request)
    {
        $query = ModuleData::where('module_id', $request->moduleId);

        if ($request->dateFrom) {
            $query->whereDate('created_at', '>=', Carbon::createFromFormat('d-m-Y', $request->dateFrom)->toDateString());
        }

        if ($request->dateTo) {
            $query->whereDate('created_at', '<=', Carbon::createFromFormat('d-m-Y', $request->dateTo)->toDateString());
        }

        return response()->json($query->get());
    }
}
