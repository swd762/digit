<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    // модуль для импорта данных с модуля и запись в таблицу

    public function import(Request $request)
    {
        // ModuleData::create([
        //     'user_id' => 1,
        //     'module_id' => 1,
        //     'temperature' => 1,
        //     'bend' => 1
        // ]);

        Log::info('module id: ' . $request->id);
        Log::info('data: ' . $request->data);
        Log::info('all: ' . json_encode($request->all()));

        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
