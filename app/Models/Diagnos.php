<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с таблицей диагнозов и ее связями
 */
class Diagnos extends Model
{
    use EagerLoadPivotTrait;

    /**
     * Имя таблицы
     */
    protected $table = 'diagnoses';

    protected $fillable = ['title', 'code'];

    public $timestamps = false;


    /**
     * Связь с таблицей со списком пациентов
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patients_diagnoses', 'diagnos_id', 'patient_id');
    }
}
