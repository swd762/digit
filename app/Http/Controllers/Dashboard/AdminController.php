<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// закрытый класс для администратора для работы с пользователями
class AdminController extends Controller
{
    // метод получения списка пользователей через api в json, либо конкретного пользователя, если прилетает параметр
    public function usersList(Request $request) {

            $id = $request->all();

            if(empty($id)) {
                $users = User::get(['id', 'name', 'first_name', 'last_name', 'email', 'middle_name']);

                foreach ($users as $user) {
                    $role = $user->role()->first()->role_name;
                    $user->role = $role;

                }
                return response()->json([
                    'status'=>'success',
                    'users'=>$users->toArray(),
                ]);
            } else {
                $user = User::find($id)->first();
                $role = $user->role()->first()->role_name;
                $user->role = $role;
                return  response()->json([
                    'status'=>'success',
                    'user'=>$user->toArray()
                ],200);
            }
    }

    // метод обновления информации пользователя при редактировании
    public function updateUser(Request $request) {
        $val = Validator::make($request->all(), [
        // в данный момент запрещено изменять login из панели администратора
        //           'name'=> 'required|unique:users',
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'update_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        $user = User::find($request->id);
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
    // метод удаления пользователя
    public function deleteUser(Request $request) {
        $user = User::find($request->id)->first();
        $user->role()->remove();
        $user->delete();

        return response()->json([
            'status'=>'success'
        ], 200);
    }
}
