<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use http\Env\Response;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
 * закрытый класс для администратора для работы с пользователями
 */

class AdminController extends Controller
{
    /**
     * метод получения пользователя через api в json
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(User $user, Request $request)
    {
        $role = $user->roles->first()->name;
        $user->role = $role;
        return response()->json([
            'status' => 'success',
            'user' => $user->toArray()
        ], 200);
    }

    /**
     * метод для мполучения списка пользователей либо, если есть входные параметры возвращает информацию о найденном
     * пользователе
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function usersList(Request $request)
    {
        if (!($request->user)) {
            $users = User::get(['id', 'name', 'first_name', 'last_name', 'email', 'middle_name']);
            foreach ($users as $user) {
                $role = $user->roles->first()->name;
                $user->role = $role;
            }
            return response()->json([
                'status' => 'success',
                'users' => $users->toArray(),
            ]);
        } else {
            $user = User::find($request->user);
            $role = $user->roles->first()->name;
            $user->role = $role;
            return response()->json([
                'status' => 'success',
                'user' => $user->toArray(),
            ]);
        }
    }

    /**
     * метод обновления информации пользователя при редактировании
     *
     * @param User $user
     * @param Request $request
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
        $user->roles()->detach();
        $user->roles()->attach($role);
        $user->role = $roleName;

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 200);
    }

    /**
     * метод удаления пользователя
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteUser(User $user, Request $request)
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
            'request' => $request->id,
            'user' => $user
        ], 200);
    }
}
