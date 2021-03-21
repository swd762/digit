<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Контроллер для управления справочником устройств сбора и передачи данных (УСПД).
 */
class ModulesController extends Controller
{
    /**
     * Метод для получения списка УСПД
     * Возвращает результат с постраничной разбивкой
     *
     * @return Json
     */
    public function getModules()
    {
        return response(Module::paginate(10));
    }

    /**
     * Метод для получения информации о конкретном УСПД
     *
     * @param Module $module - модель УСПД
     *
     * @return Json
     */
    public function getModuleInfo(Module $module)
    {
        return response()->json(
            $module
        );
    }

    /**
     * Метод для обновления данных УСПД
     * Входные параметры:
     *  Имя УСПД
     *  id УСПД
     *
     * @param Module $module - модель УСПД
     * @param Request $request
     * @var String name - имя УСПД
     * @var Int module_id - id УСПД
     *
     * @return Json
     */
    public function updateModule(Module $module, Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'module_id' => 'required|min:3'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'update_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        $module->name = $request->name;
        $module->module_id = $request->module_id;
        $module->save();

        return response()->json([
            'status' => 'success',
            'module' => $module,
        ]);
    }

    /**
     * Метод для создания УСПД
     * Входные параметры:
     *  Имя УСПД
     *  id УСПД
     *
     * @param Request $request
     * @var String name - имя УСПД
     * @var Int module_id - id УСПД
     *
     * @return Json
     */
    public function createModule(Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'module_id' => 'required|min:3'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'update_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        Module::create([
            'name' => $request->name,
            'module_id' => $request->module_id
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }

    /**
     * Метод для удаления УСПД
     *
     * @param Module $module - модель УСПД
     *
     * @return Json
     */
    public function removeModule(Module $module)
    {
        $module->delete();

        return response()->json([
            'msg' => 'success'
        ]);
    }
}
