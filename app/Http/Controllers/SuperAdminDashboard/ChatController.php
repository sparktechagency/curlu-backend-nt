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
            'message'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        $message              = new Message();
        $message->sender_id   = Auth::user()->id;
        $message->receiver_id = $request->receiver_id;
        $message->message     = $request->message;
        if ($request->file('message_image')) {
            $message->image = saveImage($request, 'message_image');
        }
        $message->save();

        return response()->json(['message' => 'Message saved successfully', 'data' => $message], 200);
    }

    public function getMessage(Request $request)
    {
        $per_page = $request->per_page ?? 10; // Default to 10 if not provided

        $messages = Message::where(function ($query) use ($request) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $request->receiver_id);
        })
            ->orWhere(function ($query) use ($request) {
                $query->where('sender_id', $request->receiver_id)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'desc') // Order messages by latest first
            ->paginate($per_page);

        return response()->json([
            'status'  => true,
            'message' => 'Messages retrieved successfully',
            'data'    => $messages,
        ]);
    }

    // public function chatList(Request $request)
    // {

    //     $mes=[];
    //     $chatList = Message::with('receiver:id,name,last_name,role_type,image')->where('sender_id', Auth::user()->id);
    //     if ($request->role_type === 'USER') {
    //         $chatList = $chatList->whereHas('receiver', function ($q) use($request) {
    //             if ($request->search) {
    //                 $q->where(function ($q) use ($request) {
    //                     $q->where('name', 'like', '%' . $request->search . '%')
    //                     ->orWhere('last_name', 'like', '%' . $request->search . '%');
    //                 });
    //             }
    //             $q->where('role_type', 'USER');
    //         });
    //     }

    //     if ($request->role_type === 'PROFESSIONAL') {
    //         $chatList = $chatList->whereHas('receiver', function ($q) use($request) {
    //             if ($request->search) {
    //                 $q->where(function ($q) use ($request) {
    //                     $q->where('name', 'like', '%' . $request->search . '%')
    //                     ->orWhere('last_name', 'like', '%' . $request->search . '%');
    //                 });
    //             }
    //             $q->where('role_type', 'PROFESSIONAL');
    //         });
    //     }
    //     $chatList = $chatList->latest('created_at')->get()->unique('receiver_id');

    //     $msg['chat_list']=$chatList;
    //     return response()->json($msg);
    // }

    // public function chatList(Request $request)
    // {

    //     $chatList = Message::with('receiver:id,name,last_name,email,role_type,image', 'sender:id,name,last_name,email,role_type,image')
    //         ->where(function ($query) use ($request) {
    //             $query->where('sender_id', Auth::user()->id)
    //                 ->orWhere('receiver_id', Auth::user()->id);
    //         });

    //     if ($request->role_type === 'USER') {
    //         $chatList = $chatList->whereHas('receiver', function ($q) use ($request) {
    //             if ($request->search) {
    //                 $q->where(function ($q) use ($request) {
    //                     $q->where('name', 'like', '%' . $request->search . '%')
    //                         ->orWhere('last_name', 'like', '%' . $request->search . '%');
    //                 });
    //             }
    //             $q->where('role_type', 'USER');
    //         })->orWhereHas('sender', function ($q) use ($request) {
    //             if ($request->search) {
    //                 $q->where(function ($q) use ($request) {
    //                     $q->where('name', 'like', '%' . $request->search . '%')
    //                         ->orWhere('last_name', 'like', '%' . $request->search . '%');
    //                 });
    //             }
    //             $q->where('role_type', 'USER');
    //         });
    //     }

    //     if ($request->role_type === 'PROFESSIONAL') {
    //         $chatList = $chatList->whereHas('receiver', function ($q) use ($request) {
    //             if ($request->search) {
    //                 $q->where(function ($q) use ($request) {
    //                     $q->where('name', 'like', '%' . $request->search . '%')
    //                         ->orWhere('last_name', 'like', '%' . $request->search . '%');
    //                 });
    //             }
    //             $q->where('role_type', 'PROFESSIONAL');
    //         })->orWhereHas('sender', function ($q) use ($request) {
    //             if ($request->search) {
    //                 $q->where(function ($q) use ($request) {
    //                     $q->where('name', 'like', '%' . $request->search . '%')
    //                         ->orWhere('last_name', 'like', '%' . $request->search . '%');
    //                 });
    //             }
    //             $q->where('role_type', 'PROFESSIONAL');
    //         });
    //     }

    //     $chatList = $chatList->latest('created_at')->get()->unique(function ($message) {
    //         return $message->sender_id === Auth::user()->id
    //         ? $message->receiver_id
    //         : $message->sender_id;
    //     });

    //     return response()->json([
    //         'status' => true,
    //         'chat_list' => $chatList,
    //     ]);
    // }

//     public function chatList(Request $request)
// {
//     $userId = Auth::user()->id;
//     $roleType = $request->role_type; // Either 'USER' or 'PROFESSIONAL'
//     $search = $request->search;

//     // Base query with eager loading
//     $chatList = Message::with([
//         'receiver:id,name,last_name,email,role_type,image',
//         'sender:id,name,last_name,email,role_type,image'
//     ])->where(function ($query) use ($userId) {
//         $query->where('sender_id', $userId)
//               ->orWhere('receiver_id', $userId);
//     });

//     // Apply role type filtering
//     if ($roleType) {
//         $chatList = $chatList->where(function ($query) use ($roleType, $search) {
//             $query->whereHas('receiver', function ($q) use ($roleType, $search) {
//                 $q->where('role_type', $roleType);
//                 if ($search) {
//                     $q->where(function ($q) use ($search) {
//                         $q->where('name', 'like', '%' . $search . '%')
//                           ->orWhere('last_name', 'like', '%' . $search . '%');
//                     });
//                 }
//             })->orWhereHas('sender', function ($q) use ($roleType, $search) {
//                 $q->where('role_type', $roleType);
//                 if ($search) {
//                     $q->where(function ($q) use ($search) {
//                         $q->where('name', 'like', '%' . $search . '%')
//                           ->orWhere('last_name', 'like', '%' . $search . '%');
//                     });
//                 }
//             });
//         });
//     }

//     // Fetch and remove duplicate chat entries
//     $chatList = $chatList->latest('created_at')->get()->unique(function ($message) use ($userId) {
//         return $message->sender_id === $userId
//             ? $message->receiver_id
//             : $message->sender_id;
//     })->values()->toArray(); // Reindex and convert to array

//     // Standard JSON response
//     return response()->json([
//         'status' => true,
//         'chat_list' => $chatList,
//     ]);
// }

    public function chatList(Request $request)
    {
        $userId   = Auth::user()->id;
        $roleType = $request->role_type; // Either 'USER' or 'PROFESSIONAL'
        $search   = $request->search;

        // Base query with eager loading
        $chatList = Message::with([
            'receiver:id,name,last_name,email,role_type,image',
            'sender:id,name,last_name,email,role_type,image',
        ])
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            });

        // Apply role type filtering
        if ($roleType) {
            $chatList = $chatList->where(function ($query) use ($roleType, $search) {
                $query->whereHas('receiver', function ($q) use ($roleType, $search) {
                    $q->where('role_type', $roleType);
                    if ($search) {
                        $q->where(function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%')
                                ->orWhere('last_name', 'like', '%' . $search . '%');
                        });
                    }
                })->orWhereHas('sender', function ($q) use ($roleType, $search) {
                    $q->where('role_type', $roleType);
                    if ($search) {
                        $q->where(function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%')
                                ->orWhere('last_name', 'like', '%' . $search . '%');
                        });
                    }
                });
            });
        }

        // Fetch and remove duplicate chat entries
        $chatList = $chatList->latest('created_at')->get()->unique(function ($message) use ($userId) {
            return $message->sender_id === $userId
            ? $message->receiver_id// Use receiver_id if the sender is the authenticated user
            : $message->sender_id; // Use sender_id if the receiver is the authenticated user
        })->values();

        // Format the response to show the other user's info only
        $chatList = $chatList->map(function ($message) use ($userId) {
            if ($message->sender_id === $userId) {
                // If authenticated user is the sender, show receiver info
                $message->user = $message->receiver;
            } else {
                // If authenticated user is the receiver, show sender info
                $message->user = $message->sender;
            }

            // Optionally remove sender and receiver from response if not needed
            unset($message->sender);
            unset($message->receiver);

            return $message;
        });

        // Standard JSON response
        return response()->json([
            'status'    => true,
            'chat_list' => $chatList,
        ]);
    }

}
