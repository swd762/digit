<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Patient;
use App\Models\Products\Product;
use Illuminate\Http\Request;

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
        return response(Product::paginate(5));
    }

    /**
     * Получаем список модулей для изделий
     *
     * @return
     */
    public function getModules()
    {
        return response(Module::get());
    }
}
