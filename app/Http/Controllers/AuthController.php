<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'password' => 'required|min:3|confirmed'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error'=>'registration_validation_error',
                'errors'=>$val->errors()
            ], 422);
        }

        $user = new User;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'status'=>'success'
        ], 200);
    }
}
