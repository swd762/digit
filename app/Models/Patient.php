<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use EagerLoadPivotTrait;
    /**
     * The attributes that are mass assignable.
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
}
