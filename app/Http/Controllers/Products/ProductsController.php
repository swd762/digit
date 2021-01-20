<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;

class ProductsController extends Controller
{
    public function index()
    {
        return response(Product::get());
    }
}
