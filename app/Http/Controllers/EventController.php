<?php

namespace App\Http\Controllers;

use App\Events\RealTimeMessage;
use App\Models\Event as ModelsEvent;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\Zoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\GoogleCalendar\Event;

class EventController extends Controller
{
    public function create(Request $request, ModelsEvent $modelsEvent, Notification $notification, Schedule $schedule)
    {
        $schedule = Schedule::where('schedule_id', $request->schedule_id)->first();
        $startDate = Carbon::parse($schedule->start_date_time);
        $endDate = Carbon::parse($schedule->end_date_time);
        $stringStartDate = date('l jS \of F Y h:i A', strtotime($startDate));
        $stringEndDate =  date('h:i A', strtotime($endDate));
        $title = session('full_name').' booked an appointment on '.$stringStartDate . ' to ' .$stringEndDate;
        $start_time = str_replace(" ", "T" ,$schedule->start_date_time);

        //merge all the needed fields in the request
        $request->merge([
            'title' => $title,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'student_id' => session('profile_id'),
            'status' => 'Pending',
            'topic' => 'Academic Advising',
            'agenda' => $title,
            'start_time' => $start_time,
            'duration' => $schedule->duration
        ]);
        //Create zoom meeting
        $zoom = Zoom::zoomCreate($request);

        //merge all the needed fields in the request
        $request->merge([
            'zoom_link' => $zoom['data']['join_url'],
            'zoom_meeting_id' => $zoom['data']['id']
        ]);

        //Save the data in the Events table
        $data = $modelsEvent::create($request->all());
        $event_id = $data->event_id;
        
        if ($data->exists) {
            //Update schedule status
            $schedule->status      = 'Pending';
            $schedule->updated_at  = date('Y-m-d H:i:s');
            $schedule->save();

            $request->merge([
                'event_id' => $event_id,
                'message' => $title,
                'sender_id' => session('profile_id'),
                'receiver_id' => $request->teacher_id
            ]);

            $arr = ([
                'title' => $title,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'student_id' => session('profile_id'),
                'status' => 'Pending'
            ]);

            $notification::create($request->all());

            broadcast(new RealTimeMessage($arr));

            $msg = 'success';
         } else {
            // failure 
         }

        return json_encode(['msg'=>$msg]);

    }    

    public function closedEvent(Request $request)
    {
        $event = ModelsEvent::where('event_id', $request->event_id)->first();

        $zoom = Zoom::deleteZoom($event->zoom_meeting_id);

        if ($zoom['success'] == 1) {
            $event->status      = 'Closed';
            $event->updated_at  = date('Y-m-d H:i:s');
            $event->save();
        }
        
        return response()->json(['message' => 'success']);
    }

    public function listAllEvents(Request $request)
    {
        $request->request->add(['per_page' => $request->input('length')]);
	    $request->request->add(['page' => (intval($request->input('start')) / intval($request->input('length'))) + 1]);
        $data = ModelsEvent::listAllEvents($request);

        return $data;
    }
}
