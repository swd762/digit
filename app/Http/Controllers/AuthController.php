<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

/**
 * Контроллер аутентификации и регистрации пользователей
 */
class AuthController extends Controller
{
    /**
     * Метод для регистрации нового пользователя. Входные данные: логин, пароль, имя, фамилия, отчество, email
     *
     * @param Request $request
     * @var String name - логин
     * @var String password - пароль
     * @var String first_name - имя
     * @var String last_name - фамилия
     * @var String middle_name - отчество
     * @var String email - email
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'first_name' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'error' => 'registration_validation_error',
                'errors' => $val->errors()
            ], 422);
        }

        $userRole = Role::where('name', 'user')->first();
        $user = new User;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;
        $user->email = $request->email;
        $user->save();
        $user->roles()->attach($userRole);

        return response()->json([
            'status' => 'success'
        ], 200);
    }


    /**
     * Метод для аутентификации пользователя.
     * Проверяет логин и пароль пользователя и возвращает ключ доступа (token) в заголовке (header)
     * В случае несовпадения - ошибку в json
     *
     * @param Request $request
     * @var String name - логин пользователя
     * @var String password - пароль пользователя
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     *
     * Метод выхода пользователя из системы.
     * Помечает ранее выданный ключ доступа некактивным
     *
     * Возвращает результат в json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully'
        ], 200);
    }

    /**
     * Метод возвращает данные о текущем пользователе в json
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $role = $user->roles->first();
        $user->role = $role->name;
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * Метод для проверки активности ранее выданного ключа доступа
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            if ($token = $this->guard()->refresh()) {
                return response()
                    ->json(['status' => 'success'], 200)
                    ->header('Authorization', $token);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'error' => 'refresh_token_error'
            ], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'error' => 'refresh_token_error'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'error' => 'token_is_invalid'
            ], 401);
        }
    }

    /**
     * Метод посредник, чтобы не обращаться напрямую к фасаду Auth
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    private function guard()
    {
        return Auth::guard();
    }
}
