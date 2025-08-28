<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\OtpMail;
use App\Models\Order;
use App\Models\Salon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SalonController extends Controller
{
         protected $fileuploadService;
    private $filePath = 'adminAsset/cover_image/';
    public function __construct(FileUploadService $file_upload_service)
    {
        $this->fileuploadService = $file_upload_service;
    }
    public function allSalon(Request $request)
    {
        $per_page= $request->per_page ?? 10;
        $query = Salon::with('user')->whereHas('user', function ($q) use ($request) {
            $q->where('role_type', 'PROFESSIONAL');

            if ($request->filled('search')) {
                $q->where(function ($q) use ($request) {
                    $q->where('address', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('last_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('email', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('phone', 'like', '%' . $request->input('search') . '%');
                });
            }
        });

        $salons = $query->latest('id')->paginate($per_page);
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
                $user->image = saveImage($request, 'image');
            }
            $user->save();

            $salon = new Salon();
            $salon->user_id = $user->id;
            $salon->experience = $request->experience;
            $salon->salon_type = $request->salon_type;
            $salon->salon_description = $request->salon_description;
            if ($request->file('id_card')) {
                $salon->id_card = saveImage($request, 'id_card');
            }
            $salon->kbis = $request->kbis;
            $salon->iban_number = $request->iban_number;
            if ($request->file('cover_image')) {
   $user->cover_image = $this->fileuploadService->setPath($this->filePath)->saveOptimizedImage($request->file('cover_image'), 40, 1320, null, true);
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

    // public function salon_invoice(Request $request)
    // {
    //     $salonInvoice = SalonInvoice::with('salon', 'salon.user:id,name,image', 'payment_detail:id,invoice_number', 'service:id,service_name');
    //     $salonInvoice = $salonInvoice->whereHas('salon.user', function ($query) {
    //         $query->where('role_type', 'PROFESSIONAL');
    //     });

    //     if ($request->filled('salon_name')) {
    //         $salonInvoice = $salonInvoice->whereHas('salon.user', function ($query) use ($request) {
    //             $query->where('name', 'LIKE', '%' . $request->salon_name . '%');
    //         });
    //     }

    //     $salonInvoice = $salonInvoice->paginate();
    //     return response()->json(['message' => 'Data retrive successfully', 'salonInvoice' => $salonInvoice], 200);
    // }

    public function salon_invoice(Request $request)
    {

        $orders = Order::with('user', 'salon', 'service');

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $orders = $orders->orWhereHas('salon', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });

            $orders = $orders->orWhereHas('service', function ($query) use ($searchTerm) {
                $query->where('service_name', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($request->has('date') && $request->date) {
            $orders = $orders->whereDate('completed_at', $request->date);
        }


        $orders = $orders->paginate($request->per_page ?? 10);

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'salon' => [
                    'image' => $order->salon->image,
                    'name' => $order->salon->name . ' ' . $order->salon->last_name,
                ],
                'service' => [
                    'service_name' => $order->service->service_name,
                ],
                'invoice_number' => $order->invoice_number,
                'confirmation_date' => $order->completed_at,
                'payment' => $order->amount,
                'curlu_earning' => $order->curlu_earning,
                'salon_earning' => $order->salon_earning,
            ];
        });

        return response()->json([
            'message' => 'Data retrieved successfully.',
            'data' => $orders,
        ], 200);
    }
}
