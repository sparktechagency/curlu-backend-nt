<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\OtpMail;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{

    public function guard()
    {
        return Auth::guard('api');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::where('email', $request->email)
            ->where('email_verified_at', null)
            ->first();

        if ($user) {
            $random = Str::random(6);
            Mail::to($request->email)->send(new OtpMail($random));
            $user->otp = $random;
            $user->email_verified_at = now();
            $user->save();

            return response(['message' => 'Please check your email for validate your email.'], 200);
        } else {
            if ($request->role_type == 'USER' || $request->role_type == 'ADMIN' || $request->role_type == 'SUPER ADMIN'){

                $user = new User();
                $user->name = $request->name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->address = $request->address;
                $user->phone = $request->phone;
                $user->role_type = $request->role_type;
                $user->date_of_birth = $request->date_of_birth;
                $user->gender = $request->gender;
                $user->otp = Str::random(6);
                if ($request->file('image')) {
                    $user->image = saveImage($request,'image');
                }
                $user->save();

                Mail::to($request->email)->send(new OtpMail($user->otp));
                return response()->json([
                    'message' => 'Please check your email to valid your email',
                ]);

            }elseif ($request->role_type == 'PROFESSIONAL'){

                DB::beginTransaction();

                try {
                    $user = new User();
                    $user->name = $request->name;
                    $user->last_name = $request->last_name;
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
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $userData = User::where('email', $request->email)->first();
        if ($userData && Hash::check($request->password, $userData->password)) {
            if ($userData->email_verified_at == null) {
                return response()->json(['message' => 'Your email is not verified'], 401);
            }
        }

        $credentials = $request->only('email', 'password');
//        return auth('api')->attempt($credentials);
        $token = $this->guard()->attempt($credentials);
        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }
        return response()->json(['message' => 'Your credential is wrong'], 402);
    }

    public function emailVerified(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }
        if ($request->otp) {
            $user = User::where('otp', $request->otp)->first();
            if ($user != null) {
                $token = $this->guard()->login($user);
            }
        }

        $user = User::where('otp', $request->otp)->first();

        if (!$user) {
            return response(['message' => 'Invalid'], 422);
        }

        $user->email_verified_at = now();
        $user->otp = 0;
//        $user->status = 'active';
        $user->save();
        //$result = app('App\Http\Controllers\NotificationController')->sendNotification('Welcome to the Barbar app', $user->created_at, $user);
        return response([
            'message' => 'Email verified successfully',
            'token' => $this->respondWithToken($token),
        ]);
    }

    public function respondWithToken($token)
    {
        $user = $this->guard()->user()->makeHidden(['otp', 'created_at', 'updated_at']);
        return response()->json([
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'bearer',
            'user' => $user,
            'expires_in' => auth('api')
                    ->factory()
                    ->getTTL() * 600000000000,  // hour*seconds
        ]);
    }

    public function loggedUserData()
    {
        if ($this->guard()->user()) {
            $user = $this->guard()->user();
//            if ($user->role_type == 'PROFESSIONAL') {
//                return response()->json([
//                    'user' => $user,
//                ]);
//            }
            return response()->json([
                'user' => $user
            ]);
        } else {
            return response()->json(['message' => 'You are unauthorized']);
        }
    }

    public function forgetPassword(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'Email not found'], 401);
        } else if ($user->google_id != null || $user->apple_id != null) {
            return response()->json([
                'message' => 'Your are social user, You do not need to forget password',
            ], 400);
        } else {
            $random = Str::random(6);
            Mail::to($request->email)->send(new OtpMail($random));
            $user->otp = $random;
            $user->email_verified_at = now();
            $user->save();
            return response()->json(['message' => 'Please check your email for get the OTP']);
        }
    }

    public function resetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Your email is not exists'
            ], 401);
        }
        if ($user->email_verified_at == null) {
            return response()->json([
                'message' => 'Your email is not verified'
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $user->update(['password' => Hash::make($request->password)]);
            return response()->json(['message' => 'Password reset successfully'], 200);
        }
    }
    public function resendOtp(Request $request)
    {
        $user = User::where('email', $request->email)
            //            ->where('verify_email', 0)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found or email already verified'], 404);
        }

        // Check if OTP resend is allowed (based on time expiration)
        $currentTime = now();
        $lastResentAt = $user->last_otp_sent_at;  // Assuming you have a column in your users table to track the last OTP sent time

        // Define your expiration time (e.g., 5 minutes)
        $expirationTime = 5;  // in minutes

        if ($lastResentAt && $lastResentAt->addMinutes($expirationTime)->isFuture()) {
            // Resend not allowed yet
            return response()->json(['message' => 'You can only resend OTP once every ' . $expirationTime . ' minutes'], 400);
        }

        // Generate new OTP
        $newOtp = Str::random(6);
        Mail::to($user->email)->send(new OtpMail($newOtp));

        // Update user data
        $user->update(['otp' => $newOtp]);
        $user->update(['email_verified_at' => now()]);
        $user->update(['last_otp_sent_at' => $currentTime]);

        return response()->json(['message' => 'OTP resent successfully']);
    }

    public function updateProfile(Request $request)
    {

        $user = $this->guard()->user();  // Assuming the user is authenticated

        if ($user->role_type == 'USER' || $user->role_type == 'ADMIN' || $user->role_type == 'SUPER ADMIN') {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'password' => 'sometimes|confirmed|min:6',
                'phone' => 'sometimes|string|max:15',
                'address' => 'sometimes|string|max:255',
                'date_of_birth' => 'sometimes|date',
                'gender' => 'sometimes|string|in:male,female,other',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $user->name = $request->name;
            $user->last_name = $request->last_name ?? $user->last_name;
            $user->phone = $request->phone ?? $user->phone;
            $user->address = $request->address ?? $user->address;
            $user->date_of_birth = $request->date_of_birth ?? $user->date_of_birth;
            $user->gender = $request->gender ?? $user->gender;
            if ($request->file('image')) {
                $user->image = saveImage($request, 'image');
            }

            $user->save();

            return response()->json([
                'message' => 'Profile updated successfully!',
                'user' => $user
            ], 200);

        } elseif ($user->role_type == 'PROFESSIONAL') {
            DB::beginTransaction();

            try {
                $this->validate($request, [
                    'name' => 'required|string|max:255',
                    'last_name' => 'sometimes|string|max:255',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                    'password' => 'sometimes|confirmed|min:6',
                    'phone' => 'sometimes|string|max:15',
                    'address' => 'sometimes|string|max:255',
                    'date_of_birth' => 'sometimes|date',
                    'gender' => 'sometimes|string|in:male,female,other',
                    'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'experience' => 'sometimes|string|max:255',
                    'salon_type' => 'sometimes|string|max:255',
                    'salon_description' => 'sometimes|string|max:500',
                    'id_card' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'kbis' => 'sometimes|string|max:255',
                    'iban_number' => 'sometimes|string|max:255',
                    'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                // Update user basic details
                $user->name = $request->name;
                $user->last_name = $request->last_name ?? $user->last_name;
                $user->email = $request->email;
                if ($request->password) {
                    $user->password = Hash::make($request->password);
                }
                $user->phone = $request->phone ?? $user->phone;
                $user->address = $request->address ?? $user->address;
                $user->date_of_birth = $request->date_of_birth ?? $user->date_of_birth;
                $user->gender = $request->gender ?? $user->gender;

                if ($request->file('image')) {
                    $user->image = saveImage($request, 'image');
                }
              // $user->save();

                // Update Professional/Salon details
                $salon = Salon::where('user_id', $user->id)->first();

                $salon->experience = $request->experience ?? $salon->experience;
                $salon->salon_type = $request->salon_type ?? $salon->salon_type;
                $salon->salon_description = $request->salon_description ?? $salon->salon_description;
                if ($request->file('id_card')) {
                    $salon->id_card = saveImage($request, 'id_card');
                }
                $salon->kbis = $request->kbis ?? $salon->kbis;
                $salon->iban_number = $request->iban_number ?? $salon->iban_number;
                if ($request->file('cover_image')) {
                    $salon->cover_image = saveImage($request, 'cover_image');
                }

                $salon->save();

                DB::commit();

                return response()->json([
                    'message' => 'Profile and salon details updated successfully!',
                    'user' => $user,
                    'salon' => $salon
                ], 200);

            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error updating profile: ' . $e->getMessage());
                return response()->json(['message' => 'Error updating profile!'], 500);
            }
        }

        return response()->json(['message' => 'Invalid role type'], 403);
    }








    /* Adding new code */

    //verify password reset
    public function emailVerifiedForResetPass(Request $request){

        // dd($request->otp);
        if(isset($request->otp)){
            $user = User::where('otp',$request->otp)->first();
            // dd($user);
            if($user){
                $validator = Validator::make($request->all(),[
                    'password' => 'required|string|min:6|confirmed'
                ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }else{
                $user->password = Hash::make($request->password);
                $user->otp = 0;
                $user->save();

                return response()->json(['message' => 'password reset successfully!'],200);
            }

            }else{
                return response()->json(['message' => 'User not found!'],404);
            }
        }else{
            return response()->json(['message' => 'Token Not Found!'],404);
        }
    }


    //user logout

    public function logout()
    {
        try{
        $this->guard()->logout();
        return response()->json(['message' => 'Successfully logged out']);
        }catch(\Exception $e){
            return response()->json(['message'=> $e->getMessage()]);
        }
    }
   

}

