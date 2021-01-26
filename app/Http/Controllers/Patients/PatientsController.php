<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
//    public function index()
//    {
//        return response(Test::all()->jsonSerialize(), 200);
//    }

    /**
     * Возвращает список пациентов
     *
     * @return
     */
    public function patientsList()
    {
        $patients = Patient::with(['diagnoses' => function ($q) {
            $q->wherePivot('active', 1);
        }, 'diagnoses.pivot.product'])->get();

        return response()->json(
            $patients
        );
    }

    /**
     * Возвращает данные о конкретном пациенте с назначенными диагнозами и изделиями
     *
     * @param Patient $patient
     *
     * @return
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
     * @return
     * @var string $diagnosComment - комментарий врача к поставленному диагнозу
     *
     * @var int $diagnosId - id диагноза для прикрепления
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
     * @return
     * @var int $diagnosId - id диагноза для удаления
     *
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
     * @return
     * @var Int productId - id изделия для прикрепления
     *
     */
    public function attachProduct(Patient $patient, $diagnosId, Request $request)
    {
        /**
         * Изделие можно прикрепить как в момент выбора диагноза, так и после.
         * Если после и прикрепляется в этот же день (Предположим, что врач в течение приема может сначала задать диагноз, а потом прикрепить изделие),
         * то запись о диагнозе оставляем прежнюю.
         * Если дата не совпадает, то старую отключаем, добавляем новую дублирующую, но уже с изделием.
         */
        $patient
            ->diagnoses()
            ->wherePivot('active', 1)
            ->updateExistingPivot($diagnosId, [
                'product_id' => $request->productId,
                'product_attach_date' => Carbon::now()->toDateString()
            ]);

        return response()->json([
            'status' => 'success',
            'msg' => 'Изделие прикреплено'
        ]);
    }

    /**
     * Открепляет устройство от диагноза
     *
     * @param Patient $patient
     * @param $diagnosId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachProduct(Patient $patient, $diagnosId, Request $request)
    {
        /**
         * Изделие можно открепить как в момент выбора диагноза, так и после.
         *
         * Если дата не совпадает, то старую отключаем, добавляем новую дублирующую, но уже с изделием.
         */
        $oldDiag = $patient->diagnosesWithPivot()
            ->wherePivot('diagnos_id', $diagnosId)
            ->wherePivot('active', 1)
            ->first();
        if ($oldDiag->pivot->issue_date == Carbon::now()->toDateString()) {
            $patient->diagnoses()
                ->wherePivot('active', 1)
                ->updateExistingPivot($diagnosId, [
                    'product_attach_date' => null,
                    'product_id' => null
                ]);
        } else {
            $updatedDiag = $oldDiag->pivot;
            $patient->diagnoses()
                ->wherePivot('active', 1)
                ->updateExistingPivot($diagnosId, [
                    'active' => 0,
                    'product_detach_date' => Carbon::now()->toDateString()
                ]);
            $patient->diagnoses()->attach($updatedDiag->diagnos_id, [
                'comment' => $updatedDiag->comment,
                'issue_date' => $updatedDiag->issue_date,
                'product_attach_date' => null,
                'product_detach_date' => null
            ]);
        }

        return response()->json([
            'status' => 'success',
            'msg' => 'Изделие откреплено'
        ]);
    }
}
