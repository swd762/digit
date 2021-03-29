<?php

namespace App\Http\Controllers\Diagnoses;

use App\Http\Controllers\Controller;
use App\Models\Diagnos;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Контроллер для управления справочником диагнозов.
 */
class DiagnosesController extends Controller
{
    /**
     * Метод для получения списка диагнозов
     * Если передан id пациента - исключается уже привязанные диагнозы из списка
     *
     * @param Request $request
     * @var Int|null patientId - id пациента
     *
     * @return Json
     */
    public function index(Request $request)
    {
        $query = Diagnos::query();
        if ($request->patientId) {
            $patient = Patient::find($request->patientId);
            $attachedDiagnosIds = $patient->diagnoses()->wherePivot('active', 1)->select('diagnoses.id')->pluck('diagnoses.id')->all();
            $query->whereNotIn('id', $attachedDiagnosIds);
        }

        if ($request->all == 1) {
            $diagnoses = $query->get();
        } else {
            $diagnoses = $query->paginate(10);
        }

        return response()->json($diagnoses);
    }

    /**
     * Метод для получения информации о конкретном диагнозе
     *
     * @param Diagnos $diagnos - модель диагноза
     *
     * @return Json
     */
    public function getDiagnosInfo(Diagnos $diagnos)
    {
        return response()->json(
            $diagnos
        );
    }

    /**
     * Метод для создания диагноза
     * Входные параметры:
     *  Имя диагноза
     *  Код диагноза
     *
     * @param Request $request
     * @var String name - имя диагноза
     * @var String code - код диагноза
     *
     * @return Json
     */
    public function create(Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'update_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        Diagnos::create([
            'title' => $request->name,
            'code' => $request->code
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }

    /**
     * Метод для обновления данных диагноза
     * Входные параметры:
     *  Имя диагноза
     *  Код диагноза
     *
     * @param Diagnos $diagnos - модель диагноза
     * @param Request $request
     * @var String title - имя диагноза
     * @var String code - код диагноза
     *
     * @return Json
     */
    public function update(Diagnos $diagnos, Request $request)
    {
        $val = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'code' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'update_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        $diagnos->title = $request->title;
        $diagnos->code = $request->code;
        $diagnos->save();

        return response()->json([
            'status' => 'success',
            'diagnos' => $diagnos,
        ]);
    }

    /**
     * Метод для удаления диагноза
     *
     * @param Diagnos $diagnos - модель диагноза
     *
     * @return Json
     */
    public function remove(Diagnos $diagnos)
    {
        $diagnos->patients()->detach();

        $diagnos->delete();

        return response()->json([
            'msg' => 'success'
        ]);
    }
}
