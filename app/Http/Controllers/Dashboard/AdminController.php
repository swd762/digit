<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function list() {
        $users = User::get(['id', 'name', 'first_name', 'last_name', 'email']);

        return response()->json([
           'status'=>'success',
           'users'=>$users->toArray()
        ]);


    }
}
