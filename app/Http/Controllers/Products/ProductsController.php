<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Patient;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     *  Класс для работы с изделиями
     */

    /**
     * Поулчаем список изделий
     *
     * @param Request $request
     * @return
     */
    public function index(Request $request)
    {
        return response(Product::paginate(10));
    }

    public function getProductInfo(Request $request)
    {
        $product = Product::find($request->product);

        return response()->json(
            $product
        );
    }

    public function updateProduct(Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required|min:3'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'update_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        $product = Product::find($request->product);
        $product->name = $request->name;
        $product->save();

        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }

    public function removeProduct(Request $request)
    {
        if (!is_null($request->product)) {
            $product = Product::find($request->product);
            $product->delete();

            return response()->json([
                'msg' => 'success',
                $product
            ]);
        }
        return response()->json([
            'msg' => 'remove error',
        ], 422);
    }

    public function createProduct(Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required|min:4'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'update_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->save();

        return response()->json([
            'msg' => 'success'
        ]);
    }

    /**
     * Получаем список модулей для изделий
     *
     * @return
     */
    public function getModules()
    {
        return response(Module::paginate(10));
    }

    public function getModuleInfo(Request $request)
    {
        $module = Module::find($request->module);

        return response()->json(
            $module
        );
    }

    public function updateModule(Request $request)
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

        $module = Module::find($request->module);
        $module->name = $request->name;
        $module->module_id = $request->module_id;
        $module->save();

        return response()->json([
            'status' => 'success',
            'module' => $module,
        ]);
    }

    public function createModule(Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required|min:4'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'update_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        $module = new Module();
        $module->name = $request->name;
        $module->module_id = $request->name;
        $module->save();

        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function removeModule(Request $request)
    {
        if (!is_null($request->module)) {
            $module = Module::find($request->module);
            $module->delete();

            return response()->json([
                'msg' => 'success',
                $module
            ]);
        }
        return response()->json([
            'msg' => 'remove error',
        ], 422);
    }

}
