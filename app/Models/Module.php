<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /**
     * модель для таблицы 'modules'
     */

    // связь с таблицей 'module data'
    public function moduleData()
    {
        return $this->hasMany(ModuleData::class);
    }

    public function currentPatient()
    {
        return $this->belongsToMany(Patient::class, 'patients_diagnoses', 'module_id', 'patient_id')
            ->using(PatientDiagnosPivot::class)
            ->wherePivot('active', 1);
    }
}
