<?php

namespace App\Http\Controllers;

use App\Events\RealTimeChat;
use App\Events\RealTimeMessage;
use App\Events\StudentReply;
use App\Events\TeacherReply;
use App\Models\Chat;
use App\Models\ChatReply;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function send(Request $request, Chat $chat, ChatReply $chatReply)
    {
        $request->merge([
            'sender_id' => session('profile_id')
        ]);

        $checking = Chat::checking($request);

        if ($checking) {
            $request->merge([
                'parent_id' => $checking->chat_id
            ]);

            $send = $chatReply::create($request->all());

            $chat = Chat::where('chat_id', $checking->chat_id)->first();
            $chat->updated_at   = date('Y-m-d H:i:s');
            $chat->save();

        } else {
            $send = $chat::create($request->all());
        }
        
        if ($send->exists) {
            $msg = 'success';
            $arr = ['msg'=>$msg];
            broadcast(new RealTimeMessage($arr));
        }
        
        return json_encode(['msg'=>$msg]);
    }

    public function reply(Request $request, ChatReply $chatReply)
    {
        Chat::asRead($request->parent_id);
        $reply = $chatReply::create($request->all());
        if ($reply->exists) {
            $msg = 'success';
            $arr = [
                'parent_id' => $request->parent_id,
                'message' => $request->message,
                'receiver_avatar' => $request->receiver_avatar,
                'receiver_id' => $request->receiver_id,
                'sender_id' => $request->sender_id,
                'created_at' => date('h:i A', strtotime(Carbon::now()))
            ];
            broadcast(new TeacherReply($arr));
            broadcast(new RealTimeMessage($arr));

            $chat = Chat::where('chat_id', $request->parent_id)->first();
            $chat->updated_at   = date('Y-m-d H:i:s');
            $chat->save();
        }

        return json_encode(['msg'=>$msg]);
    }

    public function studentReply(Request $request, ChatReply $chatReply)
    {
        Chat::asRead($request->parent_id);
        $reply = $chatReply::create($request->all());
        if ($reply->exists) {
            $msg = 'success';
            $arr = [
                'parent_id' => $request->parent_id,
                'message' => $request->message,
                'receiver_avatar' => $request->receiver_avatar,
                'receiver_id' => $request->receiver_id,
                'sender_id' => $request->sender_id,
                'created_at' => date('h:i A', strtotime(Carbon::now()))
            ];
            broadcast(new StudentReply($arr));
            broadcast(new RealTimeMessage($arr));

            $chat = Chat::where('chat_id', $request->parent_id)->first();
            $chat->updated_at   = date('Y-m-d H:i:s');
            $chat->save();
        }

        return json_encode(['msg'=>$msg]);
    }

    public function chatCount()
    {
        $data = Chat::chatCnt();

        return $data;
    }

    public function techerChatCount()
    {
        $data = Chat::teacherChatCnt();

        return $data;
    }

    public function chatDelete(Request $request)
    {
        Chat::where('chat_id', '=', $request->parent_id)->delete();
        ChatReply::where('parent_id', '=', $request->parent_id)->delete();
        return response()->json(['message' => 'success']);
    }

    public function teacherChatNotifList()
    {
        $data = Chat::teacherChatNotifList();

        return $data;
    }

    public function studentChatNotifList()
    {
        $data = Chat::studentChatNotifList();

        return $data;
    }
}
