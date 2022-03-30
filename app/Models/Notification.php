<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notif_id';
    protected $fillable = [
        'notif_id',
        'event_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read'
    ];

    public static function count($id)
    {
        $data = self::where([
            ['receiver_id', '=', $id],
            ['is_read', '=', 0]
        ])->count();

        return $data;
    }

    public static function getNotif($id)
    {
        $data = self::select('notifications.*', 'profiles.avatar', 'events.status',
                DB::raw('CONCAT(profiles.first_name," ", profiles.last_name) AS sender_name'))
                ->join('profiles', 'profiles.profile_id', 'notifications.sender_id')
                ->join('events', 'events.event_id', 'notifications.event_id')
                ->where('notifications.receiver_id', '=', $id)
                ->orderBy('notifications.created_at', 'desc')
                ->get();

        return $data;
    }
}
