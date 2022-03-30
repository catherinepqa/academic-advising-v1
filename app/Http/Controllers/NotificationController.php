<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function count()
    {
        $data = Notification::count(session('profile_id'));

        return $data;
    }

    public function getNotif()
    {
        $data = Notification::getNotif(session('profile_id'));

        return $data;
    }

    public function destroy(Request $request)
    {
        Notification::where('notif_id', $request->notif_id)->delete();
	    return response()->json(['message' => 'success']);
    }

    public function read(Request $request)
    {
        //Updating the notification
        $notif = Notification::where('notif_id', $request->notif_id)->first();
        $notif->is_read      = 1;
        $notif->updated_at   = date('Y-m-d H:i:s');
        $notif->save();

        return response()->json(['message' => 'success']);
    }

    public function deleteAll(Request $request)
    {
        Notification::where('receiver_id', session('profile_id'))->delete();
	    return response()->json(['message' => 'success']);
    }
}
