<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с таблицей хранения данных, полученных с устройств сбора и передачи данных (УСПД)
 */
class ModuleData extends Model
{
    protected $fillable = [
        'patient_id',
        'module_id',
        'temperature',
        'bend',
        'is_real',
        'created_at'
    ];

    public $timestamps = false;

    /**
     * Связь с таблицей пациентов
     */
    public function patients()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Связь с таблицей модулей
     */
    public function modules()
    {
        return $this->belongsTo(Module::class);
    }
}
