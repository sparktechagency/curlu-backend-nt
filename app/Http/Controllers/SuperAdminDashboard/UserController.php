<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function userDetails(Request $request)
    {
        $query = User::query()->where('role_type', 'USER');

        if ($request->filled('location')) {
            $query->where('address', 'like', '%' . $request->input('location') . '%');
        }
        $user = $query->paginate(10);
        return response()->json($user, 200);
    }

    public function userStatus(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if ($user->user_status == 'active') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }
        $user->user_status = $status;
        $user->save();
        return response()->json(['message' => 'Status updated'], 200);
    }
}
