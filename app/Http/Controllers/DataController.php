<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleData;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Контроллер для работы с данными с модулей
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */

    // модуль для импорта данных с модуля и запись в таблицу
    public function import(Request $request)
    {
        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
