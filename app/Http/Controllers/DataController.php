<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleData;
use Illuminate\Http\Request;

class DataController extends Controller
{
    // модуль для импорта данных с модуля и запись в таблицу

    public function import(Request $request) {

        $module = new Module;
        $module->module_name = '1';
        $module->module_key = '1';
        $module->save();
        $moduleData = new ModuleData;
        $moduleData->module_key = $module->key;
        $moduleData->data = $request->all();
        $moduleData->save();
        return response()->json([
            'status'=>'success',
        ], 200);
    }
}
