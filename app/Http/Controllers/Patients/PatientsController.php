<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Reception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Контроллер для управления пациентами. Вывод и добавление информации о диагнозах, устройствах сбора и передачи данных (УСПД),
 * протезно-ортопедических изделиях (ПОИ), приемах и т.д.
 */
class PatientsController extends Controller
{
    /**
     * Метод для получения списка пациентов
     *
     * @return Json
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
     * Метод для получения данных о конкретном пациенте с назначенными диагнозами, ПОИ и УСПД
     *
     * @param Patient $patient - модель пациента
     *
     * @return Json
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
     * Метод для добавления нового пациента
     * Входящие параметры имя, фамилия, отчество, дата рождения
     *
     * @param Request $request
     * @var String first_name - имя пациента
     * @var String last_name - фамилия пациента
     * @var String middle_name - отчество пациента
     * @var String date - Дата рождения
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function patientAdd(Request $request)
    {
        $val = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'ошибка добавления пациента',
                'errors' => $val->errors()
            ], 422);
        }
        $date = Carbon::createFromFormat('Y-m-d', $request->date);

        $name = $request->last_name . " " . $request->first_name;

        if ($request->middle_name) {
            $name .= " " . $request->middle_name;
        }

        Patient::create([
            'name' => $name,
            'birth_date' => $date->toDateString()
        ]);

        return response()->json([
            'message' => 'Пациент успешно добавлен'
        ]);
    }

    /**
     * Метод для обновления существующего пациента
     * Входящие параметры ФИО, дата рождения
     *
     * @param Request $request
     * @var String name - ФИО пациента
     * @var String date - Дата рождения
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Patient $patient, Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required',
            'birth_date' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'ошибка обновления пациента',
                'errors' => $val->errors()
            ], 422);
        }

        $patient->name = $request->name;
        $patient->birth_date = Carbon::createFromFormat('d-m-Y', $request->birth_date);
        $patient->save();

        return response()->json([
            'message' => 'Пациент успешно обновлен'
        ]);
    }

    /**
     * Метод для прикрепления диагноза к пациенту
     *
     * @param Patient $patient - модель пациента
     * @param Request $request
     * @var String diagnosComment - комментарий врача к поставленному диагнозу
     * @var Int diagnosId - id диагноза для прикрепления
     *
     * @return Json
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
     * Метод для удаления диагноза и сопутствующего ПОИ у пациента.
     * Фактически запись не удаляется, а переводится в неактивный режим
     *
     * @param Patient $patient - модель пациента
     * @param Request $request
     * @var int $diagnosId - id диагноза для удаления
     *
     * @return Json
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
     * Метод для прикрепления ПОИ к диагнозу пациента
     *
     * @param Patient $patient - модель пациента
     * @param Int $diagnosId - id диагноза, к которому необходимо прикрепить ПОИ
     * @param Request $request
     * @var Int productId - id ПОИ для прикрепления
     *
     * @return Json
     */
    public function attachProduct(Patient $patient, $diagnosId, Request $request)
    {
        /**
         * ПОИ можно прикрепить как в момент выбора диагноза, так и после.
         * Если после и прикрепляется в этот же день (Предположим, что врач в течение приема может сначала задать диагноз, а потом прикрепить ПОИ),
         * то запись о диагнозе оставляем прежнюю.
         * Если дата не совпадает, то старую отключаем, добавляем новую дублирующую, но уже с ПОИ.
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
     * Метод для открепления ПОИ от диагноза
     *
     * @param Patient $patient - модель пациента
     * @param $diagnosId - id диагноза, от которого необходимо открепить ПОИ
     *
     * @return Json
     */
    public function detachProduct(Patient $patient, $diagnosId)
    {
        /**
         * ПОИ можно открепить как в момент выбора диагноза, так и после.
         *
         * Если дата не совпадает, то старую отключаем, добавляем новую дублирующую, но уже с ПОИ.
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
     * Метод для добавления или обновления приема пациента
     *
     * @param Patient $patient - модель пациента
     * @param Request $request
     * @var Int|null id - id приема. Передается при редактировании
     * @var String comment - комментарий врача к приему
     *
     * @return Json
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
     * Метод для удаления приема пациента
     *
     * @param Patient $patient - модель пациента
     * @param Request $request
     * @var Int id - id приема
     *
     * @return Json
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
     * Метод для получения информации о наличии данных с УСПД, пока в тестовом режиме
     *
     * @return Json
     */
    public function getModuleDownloadStatus()
    {
        return response()->json([
            'status' => 'success',
            'date' => Carbon::now()->toDateString()
        ]);
    }

    /**
     * Метод для прикрепления ПОИ к диагнозу пациента
     *
     * @param Patient $patient - модель пациента
     * @param Int $diagnosId - id диагноза, к которому необходимо прикрепить ПОИ
     * @param Request $request
     * @var Int productId - id ПОИ для прикрепления
     *
     * @return Json
     */
    public function updateRecord(Patient $patient, $diagnosId, Request $request)
    {
        /**
         * ПОИ можно прикрепить как в момент выбора диагноза, так и после.
         * Если после и прикрепляется в этот же день (Предположим, что врач в течение приема может сначала задать диагноз, а потом прикрепить ПОИ),
         * то запись о диагнозе оставляем прежнюю.
         * Если дата не совпадает, то старую отключаем, добавляем новую дублирующую, но уже с ПОИ.
         */
        $patient
            ->diagnoses()
            ->wherePivot('active', 1)
            ->updateExistingPivot($diagnosId, [
                'comment' => $request->comment,
                'product_id' => $request->product_id,
                'module_id' => $request->module_id,
                'issue_date' => Carbon::parse($request->issue_date)->toDateString()
            ]);

        return response()->json([
            'status' => 'success',
            'msg' => 'Обновлено'
        ]);
    }
}
