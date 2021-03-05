<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleData extends Model
{
    /**
     * модель для таблицы 'module_data' . С данными, полученными непосредственно с модуля
     *
     * @var string[]
     */
    protected $fillable = [
        'patient_id',
        'module_id',
        'temperature',
        'bend',
        'is_real',
        'created_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    // связь с таблицей пациентов
    public function patients()
    {
        return $this->belongsTo(Patient::class);
    }

    // связь с таблицей модулей
    public function modules()
    {
        return $this->belongsTo(Module::class);
    }
}
