<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\OtpMail;
use App\Models\Salon;
use App\Models\Teacher;
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
        $user->status = 'active';
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
        if ($user->verify_email == 0) {
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


}
