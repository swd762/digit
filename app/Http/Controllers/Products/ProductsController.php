<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Контроллер для управления справочником протезно-ортопедических изделий (ПОИ).
 */
class ProductsController extends Controller
{
    /**
     * Метод для получения списка ПОИ
     * Возвращает результат с постраничной разбивкой
     *
     * @return Json
     */
    public function index(Request $request)
    {
        if ($request->all) {
            return response(Product::all());
        }
        return response(Product::paginate(10));
    }

    /**
     * Метод для получения информации о конкретном ПОИ
     *
     * @param Product $product - модель ПОИ
     *
     * @return Json
     */
    public function getProductInfo(Product $product)
    {
        return response()->json(
            $product
        );
    }

    /**
     * Метод для обновления данных ПОИ
     * Входные параметры:
     *  Имя ПОИ
     *
     * @param Product $product - модель ПОИ
     * @param Request $request
     * @var String name - имя ПОИ
     *
     * @return Json
     */
    public function updateProduct(Product $product, Request $request)
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

        $product->name = $request->name;
        $product->save();

        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }

    /**
     * Метод для удаления ПОИ
     *
     * @param Product $product - модель ПОИ
     *
     * @return Json
     */
    public function removeProduct(Product $product)
    {
        $product->delete();

        return response()->json([
            'msg' => 'success'
        ]);
    }

    /**
     * Метод для создания ПОИ
     * Входные параметры:
     *  Имя ПОИ
     * @param Request $request
     * @var String name - имя ПОИ
     *
     * @return Json
     */
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

        Product::create([
            'name' => $request->name
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }
}
