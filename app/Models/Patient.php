<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с таблицей со списком пациентов
 */
class Patient extends Model
{
    use EagerLoadPivotTrait;

    protected $fillable = ['name', 'birth_date'];

    /**
     * Связь с таблицей диагнозов
     */
    public function diagnoses()
    {
        return $this->belongsToMany(Diagnos::class, 'patients_diagnoses', 'patient_id', 'diagnos_id')
            ->using(PatientDiagnosPivot::class)
            ->withPivot('product_id');
    }

    /**
     * Связь с таблицей диагнозов с подгрузкой дополнительных данных из промежуточной таблицы
     */
    public function diagnosesWithPivot()
    {
        return $this->belongsToMany(Diagnos::class, 'patients_diagnoses', 'patient_id', 'diagnos_id')
            ->using(PatientDiagnosPivot::class)
            ->withPivot('product_id', 'issue_date', 'detach_date', 'comment');
    }

    /**
     * Связь с таблицей приемов пациентов
     */
    public function receptions()
    {
        return $this->hasMany(Reception::class);
    }

    /**
     * Связь с таблицей для хранения данных, полученных с устройств сбора и передачи данных (УСПД)
     */
    public function moduleData()
    {
        return $this->hasMany(ModuleData::class);
    }
}
