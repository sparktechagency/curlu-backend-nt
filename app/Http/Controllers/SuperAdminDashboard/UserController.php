<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function userDetails(Request $request)
    {
        $query = User::query()->where('role_type','USER');

        if($request->filled('location')){
            $query->where('address', 'like' , '%' . $request->input('location') . '%');
        }
        $user = $query->paginate(10);
        return response()->json($user,200);
    }
}
