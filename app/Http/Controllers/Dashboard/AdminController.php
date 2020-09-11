<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function list() {
        $users = User::get(['id', 'name', 'first_name', 'last_name', 'email', 'middle_name']);

        foreach ($users as $user) {
            $role = $user->role()->first()->role_name;
            $user->role = $role;

        }
        return response()->json([
           'status'=>'success',
           'users'=>$users->toArray()
        ]);
    }

    public function updateUser(Request $request) {
        $val = Validator::make($request->all(), [
//           'name'=> 'required|unique:users',

        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'update_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        $user = User::find($request->id);
//        $user->name = $request->name;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;

        $user->save();
        $user->role()->update(['user_id'=>$request->id, 'role_name'=>$request->role]);


        return response()->json([
            'status' =>'success',
            'user' => $user
        ], 200);
    }

    public function deleteUser(Request $request) {
        $user = User::find($request->id)->first();
        $user->role()->remove();
        $user->delete();

        return response()->json([
            'status'=>'success'
        ], 200);
    }
}
