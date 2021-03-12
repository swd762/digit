<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Reception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientsController extends Controller
{
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
     * Возвращает данные о конкретном пациенте с назначенными диагнозами, изделиями и модулями
     *
     * @param Patient $patient
     *
     * @return
     */
    public function patientInfo(Patient $patient)
    {
        $patient->load([
            'diagnoses' => function ($q) {
                $q->wherePivot('active', 1)->withPivot(['comment', 'issue_date', 'module_id']);
            }, 'diagnoses.pivot.product', 'diagnoses.pivot.module',
            'receptions' => function ($query) {
                $query->select(['id', 'patient_id', 'receipt_description', 'receipt_date']);
            }
        ]);


        return response()->json($patient);
    }

    /**
     * API добавление пациента
     *
     * входящие параметры Имя, фамилия, Отчество, дата рождения
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function patientAdd(Request $request)
    {
        $val = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'ошибка добавления пациента',
                'errors' => $val->errors()
            ], 422);
        }
        $date = Carbon::createFromFormat('Y-m-d',$request->date);
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $middle_name = $request->middle_name;
        $name = $last_name . " " . $first_name . " " . $middle_name;
        $patient = new Patient();
        $patient->name = $name;
        $patient->birth_date = $date->toDateString();
        $patient->save();

        return response()->json([
            'message' => 'Пациент успешно добавлен'
        ]);
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
                'module_id' => $request->moduleId,
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

    /**
     * Добавляем осмотр или обновляем существующий
     *
     *
     * @param Patient $patient
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachReception(Patient $patient, Request $request)
    {
        if ($request->id == null) {
            $reception = new Reception([
                'receipt_description' => $request->comment,
                'receipt_date' => $request->date
            ]);
            $patient->receptions()->save($reception);
        } else {

            $reception = $patient->receptions()->find($request->id);
            $reception->receipt_description = $request->comment;
            $reception->receipt_date = Carbon::now()->toDateString();
            $reception->save();
        }

        return response()->json([
            'status' => 'success',
            'msg' => 'осмотр добавлен или обновлен'
        ]);
    }

    /**
     * Удалем осмотр из списка
     *
     * @param Patient $patient
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function removeReception(Patient $patient, Request $request)
    {
        $reception = $patient->receptions()->find($request->id);
        $reception->delete();

        return response()->json([
            'status' => 'success',
            'msg' => 'осмотр удален'
        ]);
    }

    /**
     * Возвращает информацию о получении данных с модуля, пока в тестовом режиме
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModuleDownloadStatus()
    {
        return response()->json([
            'status' => 'success',
            'date' => Carbon::now()->toDateString()
        ]);
    }
}
