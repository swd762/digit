<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        foreach ($data as $item) {
            ModuleData::create([
                'patient_id' => $patient ? $patient->id : null,
                'module_id' => $module->id,
                'temperature' => $item->Temperature * 10,
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
            $query->where('created_at', '>=', Carbon::createFromFormat('d-m-Y H:i:s', $request->dateFrom)->toDateTimeString());
        }

        if ($request->dateTo) {
            $query->where('created_at', '<=', Carbon::createFromFormat('d-m-Y H:i:s', $request->dateTo)->toDateTimeString());
        }

        return response()->json($query->get());
    }

    public function getPeriods(Request $request)
    {
        $periods = [];
        $period = '';
        $dates = DB::select('select date(created_at) as date from module_data where module_id=' . (int)$request->moduleId . ' group by date(created_at)');

        if (!count($dates)) return response()->json([]);

        $prev = null;
        foreach ($dates as $oDate) {
            $date = $oDate->date;
            if (!$prev) {
                $period .= Carbon::parse($date)->format('d-m-Y');
            } else {
                $cDate = Carbon::parse($date);
                if (Carbon::parse($prev)->diffInDays($cDate) > 1) {
                    $period .= ' - ' . Carbon::parse($prev)->format('d-m-Y');
                    $periods[] = $period;
                    $period = '';
                    $period .= $cDate->format('d-m-Y');
                }
            }
            $prev = $date;
        }

        $period .= ' - ' . Carbon::parse($prev)->format('d-m-Y');
        $periods[] = $period;
        return response()->json($periods);
    }

    /**
     * Метод для запуска оценки данных в нейронной сети
     *
     * @param Request $request
     * @var Int patientId - id пациента
     * @var String dateFrom - дата начала выборки
     * @var String dateTo - дата окончания выборки
     *
     * @return json
     */
    public function runAssessment(Request $request)
    {
        $dateFrom = Carbon::createFromFormat('d-m-Y H:i:s', $request->dateFrom)->toDateTimeString();
        $dateTo = Carbon::createFromFormat('d-m-Y H:i:s', $request->dateTo)->toDateTimeString();
        $dataCount = ModuleData::where('patient_id', $request->patientId)
            ->where('module_id', $request->moduleId)
            ->where('created_at', '>=', $dateFrom)
            ->where('created_at', '<=', $dateTo)
            ->count();

        if ($dataCount == 0) {
            return response()->json(['status' => 'error', 'msg' => 'Не найдено данных за указанный период']);
        }

        $result = exec('python3 python/runAnalizing.py "{\"dateFrom\" : ' . $dateFrom . ', \"dateTo\" : ' . $dateTo . '}"');
        // $result = exec('"C:\Program Files\Python38\python.exe" python/runAnalizing.py "{\"dateFrom\" : ' . $dateFrom . ', \"dateTo\" : ' . $dateTo . '}"');

        $data = ModuleData::where('patient_id', $request->patientId)
            ->where('module_id', $request->moduleId)
            ->where('created_at', '>=', $dateFrom)
            ->where('created_at', '<=', $dateTo)
            ->get();

        $counter = 0;
        foreach ($data as $item) {
            if ($item->is_real == 1) $counter++;
        }

        return response()->json(['status' => 'success', 'msg' => 'Уровень комплаенса - ' . $counter / $data->count() * 100 . '%']);
    }
}
