<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Контроллер для управления пользователями.
 */
class AdminController extends Controller
{
    /**
     * Метод получения информации о конкретном пользователе
     *
     * @param User $user - модель пользователя
     * @return Json
     */
    public function getUser(User $user)
    {
        $role = $user->roles->first()->name;
        $user->role = $role;
        return response()->json([
            'status' => 'success',
            'user' => $user->toArray()
        ], 200);
    }

    /**
     * Метод для получения списка пользователей
     *
     * @return Json
     */
    public function usersList()
    {
        $users = User::get(['id', 'name', 'first_name', 'last_name', 'email', 'middle_name']);
        foreach ($users as $user) {
            $role = $user->roles->first()->name;
            $user->role = $role;
        }
        return response()->json([
            'status' => 'success',
            'users' => $users->toArray(),
        ]);
    }

    /**
     * Метод для обновления данных пользователя
     * Входные данные:
     * email,
     * ФИО,
     * роль
     *
     * @param User $user - модель пользователя
     * @param Request $request
     * @var String email - email пользователя
     * @var String first_name - имя пользователя
     * @var String last_name - фамилия пользователя
     * @var String middle_name - отчество пользователя
     * @var String role - имя роли пользователя
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(User $user, Request $request)
    {
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

        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;
        $user->save();

        $roleName = is_null($request->role) ? 'user' : $request->role;
        $role = Role::where('name', $roleName)->first();

        $user->roles()->sync($role->id);
        $user->role = $roleName;

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 200);
    }

    /**
     * Метод для удаления пользователя
     *
     * @param User $user - модель пользователя
     *
     * @return Json
     */
    public function deleteUser(User $user)
    {
        if ($user->name == 'admin' || $user->name == 'saul') {
            return response()->json([
                'status' => 'error',
                'error' => 'you can\'t delete admin',
            ], 422);
        }
        $user->roles()->detach();
        $user->delete();

        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 200);
    }
}
