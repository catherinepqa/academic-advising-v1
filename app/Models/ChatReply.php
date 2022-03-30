<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatReply extends Model
{
    use HasFactory;

    protected $primaryKey = 'reply_id';
    protected $fillable = [
        'parent_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read'
    ];

    public static function replies($parent_id)
    {
        $data = self::select('chat_replies.*')
                ->where([
                    ['parent_id', '=', $parent_id]
                ])
                ->get();   
        return $data;
    }
}
