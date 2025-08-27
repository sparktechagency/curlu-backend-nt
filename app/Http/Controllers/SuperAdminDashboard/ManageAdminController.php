<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManageAdminController extends Controller
{

    public function index(Request $request)
    {
        return User::whereIn('role_type', ['ADMIN', 'SUPER ADMIN'])->paginate($request->per_page??10);
    }

    public function store(AdminRequest $request)
    {
        $admin = new User();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->role_type = $request->role_type;
        $admin->otp = 0;
        $admin->email_verified_at = new Carbon();
        if ($request->file('image')) {
            $admin->image = saveImage($request, 'image');
        }
        $admin->save();
        return response()->json(['message' => 'Role assign successfully', 'data' => $admin]);
    }

    public function show(string $id)
    {
        $admin = User::whereIn('role_type', ['ADMIN', 'SUPER ADMIN'])->where('id', $id)->first();
        if (empty($admin)) {
            return response()->json(['message' => 'User Does Not Exist'], 404);
        }
        return response()->json($admin);
    }

    public function update(Request $request, string $id)
    {
        $admin = User::whereIn('role_type', ['ADMIN', 'SUPER ADMIN'])->where('id', $id)->first();
        if (empty($admin)) {
            return response()->json(['message' => 'User Does Not Exist'], 404);
        }
        $admin->name = $request->name ?? $admin->name;
        $admin->email = $request->email ?? $admin->email;
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        $admin->role_type = $request->role_type ?? $admin->role_type;
        if ($request->file('image')) {
            if (!empty($admin->image)) {
                removeImage($admin->image);
            }
            $admin->image = saveImage($request, 'image');
        }
        $admin->save();
        return response()->json(['message' => 'Admin updated successfully', 'data' => $admin]);
    }

    public function destroy(string $id)
    {
        $admin = User::whereIn('role_type', ['ADMIN', 'SUPER ADMIN'])->where('id', $id)->first();
        if (empty($admin)) {
            return response()->json(['message' => 'User Does Not Exist'], 404);
        }
        if ($admin->image) {
            removeImage($admin->image);
        }
        $admin->delete();
        return response()->json(['message' => 'Admin deleted successfully']);
    }
}
