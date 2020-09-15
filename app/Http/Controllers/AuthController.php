<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // Контроллер аутентификации и регистрации пользователей

    // метод регистрации нового пользователя
    public function register(Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'registration_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        $user = new User;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
//        $user->first
        $user->save();
        $user->role()->create(['user_id'=>$user->id,'role_name'=>'user']);

        return response()->json([
            'status' => 'success'
        ], 200);
    }


    // метод для аутентификации пользователя (проверяет "паспорт" -> возвращает статус в json и token в хэдере)
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json([
                'status' => 'success'
            ], 200)->header('Authorization', $token);
        }
        return response()->json([
            'error' => 'error_login',
        ], 401);
    }
    // метод выхода пользователя (возвращает статус в json)
    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully'
        ], 200);
    }

    // метод чтения данных пользвателя для последующей авторизации
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $role = $user->role()->first()->role_name;
        $user->role = $role;

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
    // метод проверки "свежести" пользователя и токена
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'success'], 200)
                ->header('Authorization', $token);
        }

        return response()->json([
            'error' => 'refresh_token_error'
        ], 401);
    }


    // метод посредник, чтобы не обращаться напрямую к фасаду Auth
    private function guard()
    {
        return Auth::guard();
    }
}
