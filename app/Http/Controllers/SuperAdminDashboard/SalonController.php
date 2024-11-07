<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SalonController extends Controller
{
    public function allSalon(Request $request)
    {
        $query = Salon::with('user');

        if ($request->filled('location'))
        {
            $query->whereHas('user' , function ($q) use ($request){
                $q->where('address', 'like', '%' . $request->input('location') . '%');
            });
        }

        $salons = $query->paginate(10);
        return response()->json($salons, 200);
    }


    public function addSalon(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->date_of_birth = $request->date_of_birth;
            $user->gender = $request->gender;
            $user->role_type = $request->role_type;
            $user->otp = Str::random(6);
            if ($request->file('image')) {
                $user->image = saveImage($request,'image');
            }
            $user->save();

            $salon = new Salon();
            $salon->user_id = $user->id;
            $salon->experience = $request->experience;
            $salon->salon_type = $request->salon_type;
            $salon->salon_description = $request->salon_description;
            if ($request->file('id_card')) {
                $salon->id_card = saveImage($request,'id_card');
            }
            $salon->kbis = $request->kbis;
            $salon->iban_number = $request->iban_number;
            if ($request->file('cover-image')) {
                $salon->cover_image = saveImage($request,'cover-image');
            }
            $salon->save();
            DB::commit();
            Mail::to($request->email)->send(new OtpMail($user->otp));
            return response()->json([
                'message' => 'Please check your email to valid your email',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding provider: ' . $e->getMessage());
            throw $e;
        }
    }

    public function salonStatus(Request $request, $id)
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
