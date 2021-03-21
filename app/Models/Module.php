<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с таблицей устройств сбора и передачи данных (УСПД)
 */
class Module extends Model
{
    /**
     * Связь с таблицей хранения данных, полученных с УСПД
     */
    public function moduleData()
    {
        return $this->hasMany(ModuleData::class);
    }

    /**
     * Связь с записью текущего пациента в таблице со списком пациентов
     */
    public function currentPatient()
    {
        return $this->belongsToMany(Patient::class, 'patients_diagnoses', 'module_id', 'patient_id')
            ->using(PatientDiagnosPivot::class)
            ->wherePivot('active', 1);
    }
}
