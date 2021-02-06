<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
    /**
     * table for this model
     *
     * @var string
     */
    protected $table = 'receptions';

    protected $fillable = ['receipt_description', 'receipt_date'];

    //
}
