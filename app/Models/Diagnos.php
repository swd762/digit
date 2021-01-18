<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Model;

class Diagnos extends Model
{
    use EagerLoadPivotTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'diagnoses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'code'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patients_diagnoses', 'diagnos_id', 'patient_id');
    }
}
