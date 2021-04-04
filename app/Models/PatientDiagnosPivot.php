<?php

namespace App\Models;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Модель для работы с промежуточной (pivot) таблицей, связывающей таблицы с пациентами и диагнозами.
 * В ней дополнительно хранятся данные прикрепленных протезно-ортопедических изделиях (ПОИ), устройствах сбора и передачи данных (УСПД),
 * датах их установки и снятия, а так же датах постановки и снятия диагнозов
 *
 * Так же используется для логирования действий связанных с постановкой и снятием диагнозов, ПОИ и УСПД.
 */
class PatientDiagnosPivot extends Pivot
{
    /**
     * Имя таблицы
     */
    protected $table = 'patients_diagnoses';

    public $timestamps = false;

    public $incrementing = true;

    /**
     * Связь с таблицей протезно-ортопедических изделий
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Связь с таблицей устройств сбора и передачи данных
     */
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    /**
     * Связь с таблицей диагнозов
     */
    public function diagnos()
    {
        return $this->belongsTo(Diagnos::class, 'diagnos_id', 'id');
    }
}
