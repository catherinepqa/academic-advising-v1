<?php

namespace App\Models;

use App\Events\RealTimeMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chat extends Model
{
    use HasFactory;

    protected $primaryKey = 'chat_id';
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read'
    ];

    public static function checking($request)
    {
        $data = self::where([
            ['sender_id', '=', $request->sender_id],
            ['receiver_id', '=', $request->receiver_id]
        ])->first();

        return $data;
    }

    public static function chatList()
    {
        $arr = [];
        $data = self::select('chats.*', 
                        DB::raw('CONCAT(profiles.first_name," ", profiles.last_name) AS receiver_name'),
                        'profiles.avatar as receiver_avatar',
                        DB::raw('COUNT(CASE chats.is_read WHEN 0 THEN 1 ELSE NULL END) AS unread'),
                        DB::raw('MAX(chat_replies.reply_id) AS reply_id')
                    )
                    ->join('profiles', 'profiles.profile_id', 'chats.receiver_id')
                    ->leftJoin('chat_replies', 'chat_replies.parent_id', 'chats.chat_id')
                    ->where([
                        ['chats.sender_id', '=', session('profile_id')]
                    ])
                    ->groupBy('receiver_id')
                    ->orderBy('chats.updated_at', 'desc')
                    ->get();                     

        foreach ($data as $row) {

            //Get tHe last chat
            $data1 = DB::table('chat_replies')
                ->select('message as reply_message', 'sender_id as reply_sender', 'created_at as reply_created_at', 'is_read')
                ->where([
                    ['reply_id', '=', $row->reply_id]
                ])
                ->orderBy('chat_replies.updated_at', 'desc')
                ->get();

            $replyCnt = DB::table('chat_replies')
                        ->where([
                            ['parent_id', '=', $row->chat_id],
                            ['is_read', '=', 0]
                        ])
                        ->count();  

            $count = self::where([
                            ['chat_id', '=', $row->chat_id],
                            ['is_read', '=', 0]
                        ])
                        ->count();             
            
            if (count($data1) == 0) {
                $d = [
                    'chat_id'           => $row->chat_id,
                    'unread'            => $count  + $replyCnt,
                    'message'           => substr($row->message, 0, 40),
                    'reply_last_sender' => $row->sender_id,
                    'receiver_name'     => $row->receiver_name,
                    'receiver_id'       => $row->receiver_id,
                    'receiver_avatar'   => $row->receiver_avatar,
                    'parent_id'         => $row->chat_id,
                    'created_at'        => date('h:i A', strtotime($row->created_at)), 
                    'is_read'           => $row->is_read
                ];
                array_push($arr, $d);  
            } else {
                foreach ($data1 as $row1) {
                    $d = [
                            'chat_id'           => $row->chat_id,
                            'unread'            => $count  + $replyCnt,
                            'message'           => substr($row1->reply_message, 0, 40),
                            'reply_last_sender' => $row1->reply_sender,
                            'receiver_name'     => $row->receiver_name,
                            'receiver_id'       => $row->receiver_id,
                            'receiver_avatar'   => $row->receiver_avatar,
                            'parent_id'         => $row->chat_id,
                            'created_at'        => date('h:i A', strtotime($row1->reply_created_at)), 
                            'is_read'           => $row->is_read == 0 ? $row1->is_read : $row->is_read
                        ];
                    array_push($arr, $d);  
                }  
            }           
        }
       
        return $arr;            
    }

    public static function chatData($parent_id)
    {
        $data = self::select('chats.*')
                ->where([
                    ['chat_id', '=', $parent_id]
                ])
                ->get();   
        return $data;
    }

    public static function teacherChatList()
    {
        $arr = [];
        $data = self::select('chats.*', 
                        DB::raw('CONCAT(profiles.first_name," ", profiles.last_name) AS sender_name'),
                        'profiles.avatar as sender_avatar',
                        DB::raw('COUNT(CASE chats.is_read WHEN 0 THEN 1 ELSE NULL END) AS unread'),
                        DB::raw('MAX(chat_replies.reply_id) AS reply_id')
                    )
                    ->join('profiles', 'profiles.profile_id', 'chats.sender_id')
                    ->leftJoin('chat_replies', 'chat_replies.parent_id', 'chats.chat_id')
                    ->where([
                        ['chats.receiver_id', '=', session('profile_id')]
                    ])
                    ->groupBy('chats.sender_id')
                    ->orderBy('chats.updated_at', 'desc')
                    ->get();          

        foreach ($data as $row) {
            //Get tHe last chat
            $data1 = DB::table('chat_replies')
                ->select('message as reply_message', 'sender_id as reply_sender', 'created_at as reply_created_at', 'is_read')
                ->where([
                    ['reply_id', '=', $row->reply_id]
                ])
                ->orderBy('chat_replies.updated_at', 'desc')
                ->get();

            $replyCnt = DB::table('chat_replies')
                ->where([
                    ['parent_id', '=', $row->chat_id],
                    ['is_read', '=', 0]
                ])
                ->count();  

            $count = self::where([
                        ['chat_id', '=', $row->chat_id],
                        ['is_read', '=', 0]
                    ])
                    ->count(); 
                    
            if (count($data1) == 0) {
                $d = [
                        'chat_id'           => $row->chat_id,
                        'unread'            => $count  + $replyCnt,
                        'message'           => substr($row->message, 0, 40),
                        'reply_last_sender' => $row->sender_id,
                        'sender_name'       => $row->sender_name,
                        'sender_id'         => $row->sender_id,
                        'sender_avatar'     => $row->sender_avatar,
                        'parent_id'         => $row->chat_id,
                        'created_at'        => date('h:i A', strtotime($row->created_at)), 
                        'is_read'           => $row->is_read
                    ];
                array_push($arr, $d);  
            } else {
                foreach ($data1 as $row1) {
                    $d = [
                            'chat_id'           => $row->chat_id,
                            'unread'            => $count  + $replyCnt,
                            'message'           => substr($row1->reply_message, 0, 40),
                            'reply_last_sender' => $row1->reply_sender,
                            'sender_name'       => $row->sender_name,
                            'sender_id'         => $row->sender_id,
                            'sender_avatar'     => $row->sender_avatar,
                            'parent_id'         => $row->chat_id,
                            'created_at'        => date('h:i A', strtotime($row1->reply_created_at)), 
                            'is_read'           => $row->is_read == 0 ? $row1->is_read : $row->is_read
                        ];
                    array_push($arr, $d);  
                }  
            }            
        }

        return $arr;            
    }

    public static function asRead($parent_id)
    {
        $data = ['is_read' => 1, 'updated_at' => date('Y-m-d H:i:s')];

        DB::table('chats')
                ->where([
                    ['receiver_id', session('profile_id')],
                    ['chat_id', $parent_id]
                ])
                ->update($data);

        DB::table('chat_replies')
            ->where([
                ['receiver_id', session('profile_id')],
                ['parent_id', $parent_id]
            ])
            ->update($data);    
            
        broadcast(new RealTimeMessage($data));    
        return true;    
    }

    public static function chatCnt()
    {
        $sum = '';

        if (session('role') == 'teacher') {
            $data = self::select('chats.chat_id', 'chats.sender_id')
                        ->where([
                            ['receiver_id', '=', session('profile_id')]
                        ])
                        ->get();          

            foreach ($data as $row) {
                $replyCnt = DB::table('chat_replies')
                            ->where([
                                //['parent_id', '=', $row->chat_id],
                                ['receiver_id', '=', session('profile_id')],
                                ['is_read', '=', 0]
                            ])
                            ->distinct()
                            //->groupBy('chat_replies.parent_id')
                            ->count('chat_replies.parent_id');  

                $count = self::where([
                                ['chat_id', '=', $row->chat_id],
                                ['is_read', '=', 0]
                            ])
                            ->count(); 
                $sum = $count + $replyCnt;   
            }
        } else {
            $replyCnt = DB::table('chat_replies')
                        ->where([
                            //['parent_id', '=', $row->chat_id],
                            ['receiver_id', '=', session('profile_id')],
                            ['is_read', '=', 0]
                        ])
                        ->distinct()
                        //->groupBy('chat_replies.parent_id')
                        ->count('chat_replies.parent_id');  
            $sum = $replyCnt;   
        }

        return $sum;
    }
}
