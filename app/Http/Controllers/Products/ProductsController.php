<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
//        $query = Product::query();
//        if ($request->patientId) {
//            $patient = Patient::find($request->patientId);
//            $attachedProductsIds = $patient->diagnoses()->wherePivot('active',1)->select()
//        }

        return response(Product::get());
    }
}
