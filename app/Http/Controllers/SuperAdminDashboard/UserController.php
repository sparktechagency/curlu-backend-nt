<?php
namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function userDetails(Request $request)
    {
       
        $query = User::where('role_type', 'USER');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('address', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('name', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('last_name', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('email', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('phone', 'like', '%' . $request->input('search') . '%');
            });
        }

        $users = $query->paginate($request->per_page??10);

        return response()->json($users, 200);
    }

    public function userStatus(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if ($user->is_blocked == 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        $user->is_blocked = $status;
        $user->save();
        return response()->json(['message' => 'Status updated', 'data' => $user], 200);
    }
}
