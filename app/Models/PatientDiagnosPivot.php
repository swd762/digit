<?php

namespace App\Models;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PatientDiagnosPivot extends Pivot
{
    /**
     * Модель для pivot таблицы Пациента и диагнозов. В ней хранятся данные о диагнозах, прикрепленных изделиях,
     * их модулях
     *
     * @var string
     */
    protected $table = 'patients_diagnoses';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
