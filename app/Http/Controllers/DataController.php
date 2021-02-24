<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleData;
use Illuminate\Http\Request;

class DataController extends Controller
{
    // модуль для импорта данных с модуля и запись в таблицу

    public function import(Request $request)
    {
        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
