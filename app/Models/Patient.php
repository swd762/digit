<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use EagerLoadPivotTrait;
    /**
     * Модель для таблицы с данными пациентов
     *
     * @var array
     */
    protected $fillable = ['name', 'birth_date'];

    public function diagnoses()
    {
        return $this->belongsToMany(Diagnos::class, 'patients_diagnoses', 'patient_id', 'diagnos_id')
            ->using(PatientDiagnosPivot::class)
            ->withPivot('product_id');
    }

    public function diagnosesWithPivot()
    {
        return $this->belongsToMany(Diagnos::class, 'patients_diagnoses', 'patient_id', 'diagnos_id')
            ->using(PatientDiagnosPivot::class)
            ->withPivot('product_id', 'issue_date', 'detach_date', 'comment' );
    }

    public function receptions()
    {
        return $this->hasMany(Reception::class);
    }

    public function moduleData()
    {
        return $this->hasMany(ModuleData::class);
    }
}
