<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function searchUser(Request $request)
    {
        $user = User::where('name', 'LIKE', '%' . $request->name . '%')->select('id', 'name', 'last_name', 'image')->get();
        return response()->json(['user' => $user], 200);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|numeric',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        $message = Message::create([
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);
        return response()->json(['message' => 'Message saved successfully'], 200);
    }

    public function getMessage(Request $request)
    {
        $messages = Message::where('sender_id', Auth::user()->id)->where('receiver_id', $request->receiver_id)
            ->get();
        return response()->json($messages);
    }

    public function chatList(Request $request)
    {

        $mes=[];
        $chatList = Message::with('receiver:id,name,last_name,role_type,image')->where('sender_id', Auth::user()->id);
        if ($request->role_type === 'USER') {
            $chatList = $chatList->whereHas('receiver', function ($q) use($request) {
                if ($request->search) {
                    $q->where(function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
                }
                $q->where('role_type', 'USER');
            });
        }

        if ($request->role_type === 'PROFESSIONAL') {
            $chatList = $chatList->whereHas('receiver', function ($q) use($request) {
                if ($request->search) {
                    $q->where(function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
                }
                $q->where('role_type', 'PROFESSIONAL');
            });
        }
        $chatList = $chatList->latest('created_at')->get()->unique('receiver_id');

        $msg['chat_list']=$chatList;
        return response()->json($msg);
    }

}
