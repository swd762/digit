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

    /**
     * Возвращает данные о конкретном пациенте с назначенными диагнозами и изделиями
     *
     * @param Patient $patient
     *
     * @return json
     */
    public function patientInfo(Patient $patient)
    {
        $patient->load(['diagnoses' => function ($q) {
            $q->wherePivot('active', 1)->withPivot(['comment', 'issue_date']);
        }, 'diagnoses.pivot.product']);

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
        /**
         * todo У одного пациента не может быть 2-х одинаковых диагнозов. Надо добавить проверку.
         */
        $patient->diagnoses()->attach($request->diagnosId, [
            'comment' => $request->diagnosComment,
            'issue_date' => Carbon::now()->toDateString()
        ]);

        return response()->json(['status' => 'success', 'msg' => 'Диагноз добавлен']);
    }


    /**
     * Удаляет диагноз и сопутствующее изделие у пациента.
     * Фактически запись не удаляется, а переводится в неактивный режим
     *
     * @param Patient $patient
     * @param Request $request
     * @var int $diagnosId - id диагноза для удаления
     *
     * @return json
     */
    public function detachDiagnos(Patient $patient, Request $request)
    {
        $patient->diagnoses()->wherePivot('active', 1)->updateExistingPivot($request->diagnosId, [
            'active' => 0,
            'detach_date' => Carbon::now()->toDateString()
        ]);

        return response()->json(['status' => 'success', 'msg' => 'Диагноз удален']);
    }

    /**
     * Прикрепляет изделие к диагнозу пациента
     *
     * @param Patient $patient
     * @param Int $diagnosId
     * @param Request $request
     * @var Int productId - id изделия для прикрепления
     *
     * @return json
     */
    public function attachProduct(Patient $patient, $diagnosId, Request $request)
    {
        /**
         * Изделие можно прикрепить как в момент выбора диагноза, так и после.
         * Если после и прикрепляется в этот же день (Предположим, что врач в течение приема может сначала задать диагноз, а потом прикрепить изделие),
         * то запись о диагнозе оставляем прежнюю.
         * Если дата не совпадает, то старую отключаем, добавляем новую дублирующую, но уже с изделием.
         */
        $patient->diagnoses()->wherePivot('active', 1)->updateExistingPivot($diagnosId, [
            'product_id' => $request->productId
        ]);

        return response()->json(['status' => 'success', 'msg' => 'Изделие прикреплено']);
    }
}
