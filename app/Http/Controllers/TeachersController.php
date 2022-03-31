<?php

namespace App\Http\Controllers;

use App\Events\RealTimeMessage;
use App\Http\Requests\UserRequest;
use App\Models\Chat;
use App\Models\ChatReply;
use App\Models\Event;
use App\Models\Messages;
use App\Models\Notification;
use App\Models\Profile;
use App\Models\Schedule;
use App\Models\ScheduleTime;
use App\Models\User;
use App\Models\Zoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.teachers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user, Profile $profile)
    {
        $msg = '';
        $password = Hash::make('12345678');
        $request->merge(['password' => $password, 'role' => 'teacher', 'email_verified_at' => Carbon::now()]);
        $data = $user::create($request->all());

        if ($data->exists) {
            $user = User::where('email', $request->email)->first();
            $request->merge(['user_id' => $user->id]);
            $profile::create($request->all());
            //$dt = ['email' => $userRequest->email, 'id' => $user->id];
            //Notification::send($user, new RegistrationEmail($dt));
            $msg = 'success';
         } else {
            // failure 
         }

        return json_encode(['msg'=>$msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        $data = $profile::list('teacher');

        $array = json_decode( json_encode($data), true);
        return response()->json(['data' => $array]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $data = Profile::getDetailsById($request->id);

        return json_encode($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile, User $user)
    {
        try {
            //Saving data to profiles table
            $profile = Profile::where('profile_id', $request->profile_id)->first();
            $profile->first_name        = $request->first_name;
            $profile->last_name         = $request->last_name;
            $profile->employee_number   = $request->employee_number;
            $profile->contact_number    = $request->contact_number;
            $profile->updated_at        = date('Y-m-d H:i:s');
            $profile->save();

            //Saving data to users table
            $user = User::find($request->user_id);
            $user->email        = $request->email;
            $user->updated_at   = date('Y-m-d H:i:s');
            $user->save();

		    return response()->json(['message' => 'success']);
	    } catch (\Exception $e) {
		    return response()->json(['message' => $e->getMessage()]);
	    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Profile::where('profile_id', $request->profile_id)->delete();
        User::findOrFail($request->user_id)->delete();
	    return response()->json(['message' => 'success']);
    }

    public function profile()
    {
        $id = session('profile_id');
        $data = Profile::getDetailsById($id);

        return view('teachers.profile', ['data' => $data]);
    }

    public function updateProfile(Request $request)
    {
        //Saving data to profiles table
        $profile = Profile::where('profile_id', $request->profile_id)->first();
        $profile->first_name        = $request->first_name;
        $profile->last_name         = $request->last_name;
        $profile->student_number    = $request->student_number;
        $profile->contact_number    = $request->contact_number;
        $profile->birthday          = $request->birthday;
        $profile->address           = $request->address;
        $profile->updated_at        = date('Y-m-d H:i:s');
        $profile->save();

        //Saving data to users table
        $user = User::find($request->user_id);
        $user->email        = $request->email;
        $user->updated_at   = date('Y-m-d H:i:s');
        $user->save();

        //Update Avatar
        if (!empty($request->avatar)) {
            Profile::updateAvatar($request);
        }

        //Update Session
        User::setSession($request->user_id, $request->role);

        return response()->json(['message' => 'success']);
    }

    public function notifications()
    {
        $data = Notification::getNotif(session('profile_id'));
        return view('teachers.notifications', ['data' => $data]);
    }

    public function acceptEvent(Request $request, Notification $notification, Schedule $schedule)
    {
        $status = $request->status;
        $event_status = $status == 'approved' ? 'Approved' : 'Declined';
        $sched_status = $status == 'approved' ? 'Approved' : 'Active';

        //Updating the event
        $event = Event::where('event_id', $request->event_id)->first();
        $event->status       = $event_status;
        $event->updated_at   = date('Y-m-d H:i:s');
        $event->save();

         //Updating the Schedule
        $schedule = Schedule::where('schedule_id', $event->schedule_id)->first();
        $schedule->status       = $sched_status;
        $schedule->updated_at   = date('Y-m-d H:i:s');
        $schedule->save();

        //Updating the notification
        $notif = Notification::where('notif_id', $request->notif_id)->first();
        $notif->is_read      = 1;
        $notif->updated_at   = date('Y-m-d H:i:s');
        $notif->save();

        //Send a notification to the student
        $stringStartDate = date('l jS \of F Y h:i A', strtotime($schedule->start_date_time));
        $stringEndDate =  date('h:i A', strtotime($schedule->end_date_time));
        
        if ($status == 'approved') {
            $title = session('full_name').' '.$status.' the appointment that you booked on '.$stringStartDate . ' to ' .$stringEndDate;
        } else {
            $title = session('full_name').' '.$status.' the appointment that you booked on '.$stringStartDate . ' to ' .$stringEndDate. 
                     ' due to this reason: '.$request->reason;

            //Delete the zoom meeting in zoom if the teacher decline the request
            Zoom::deleteZoom($event->zoom_meeting_id);
        }
        
        $request->merge([
            'event_id' => $request->event_id,
            'message' => $title,
            'sender_id' => session('profile_id'),
            'receiver_id' => $event->student_id
        ]);
        $request->request->remove('notif_id');
        $notification::create($request->all());

        $arr = ([
            'title' => $title,
            'sender_id' => session('profile_id') 
        ]);
        broadcast(new RealTimeMessage($arr));

        return response()->json(['message' => 'success']);
    }

    public function eventList()
    {
        $data = Event::eventList();

	    return empty($data) ? [] : $data;
    }

    public function chat()
    {
        return view('teachers.chat');
    }

    public function chatList()
    {
        $data = Chat::teacherChatList();

        return $data;
    }

    public function chatData(Request $request)
    {
        if ($request->ajax())
        {
            $data = Chat::chatData($request->parent_id);
            $replies = ChatReply::replies($request->parent_id);
            $parent_id = $request->parent_id;
            $sender = Profile::getDetailsById($request->sender_id);
            Chat::asRead($parent_id);
        }
    
        return view('teachers.chat_page', compact('data', 'parent_id', 'sender', 'replies'))->render();
    }

    public function schedule(Request $request, Schedule $schedule)
    {
        $arr = [];
        $thisWeekMonday = date('Y-m-d',time()+( 1 - date('w'))*24*3600);
        $thisWeekFriday = date('Y-m-d',time()+( 5 - date('w'))*24*3600);
        $startOfMonth = date('Y-m-d', strtotime(Carbon::now()->startOfMonth()));
        $endOfMonth = date('Y-m-d', strtotime(Carbon::now()->endOfMonth()));

        for ($i=1; $i < 6; $i++)
        {
            $date = date('Y-m-d',time()+( $i - date('w'))*24*3600);
            $d = ['date' => $date, 'day' => date('l', strtotime($date))];
            array_push($arr, $d);
        }

        $list = Schedule::scheduleList($thisWeekMonday, $thisWeekFriday, session('profile_id'));

        return view('teachers.schedule', ['dates' => $arr, 'list' => $list, 'monday' => $thisWeekMonday,
                                          'friday' => $thisWeekFriday, 'startOfMonth' => $startOfMonth,
                                          'endOfMonth' => $endOfMonth]);
    }

    public function addTime(Request $request, Schedule $schedule)
    {
        $msg = '';
        $startTime = strtotime($request->start_time);
        $endTime = strtotime($request->end_time);
        $totalSecondsDiff = abs($startTime - $endTime);
        $totalMinutesDiff = $totalSecondsDiff/60;
        $date = date('Y-m-d', strtotime($request->date));

        $request->merge([
            'teacher_id'        => session('profile_id'),
            'start_date_time'   => $date.' '.$request->start_time,
            'end_date_time'     => $date.' '.$request->end_time,
            'date'              => $date,
            'duration'          => $totalMinutesDiff,
            'status'            => 'Active'
        ]);

        $data = $schedule::create($request->all());

        if ($data->exists) {
            $msg = 'success';
        }

        return json_encode(['msg'=>$msg]);
    }

    public function scheduleList(Request $request)
    {
        $arr = [];
        $data = '';
        $list = Schedule::scheduleList($request->monday, $request->friday, $request->teacher_id, 'Active');

        foreach ($list as $li) {
            $data = [
                'start'         => date('h:i A', strtotime($li->start_date_time)),
                'end'           => date('h:i A', strtotime($li->end_date_time)),
                'day'           => $li->day,
                'schedule_id'   => $li->schedule_id
            ];
            array_push($arr, $data);
        }
        return json_encode($arr);
    }

    public function destroySchedule(Request $request)
    {
        Schedule::where('schedule_id', $request->schedule_id)->delete();
	    return response()->json(['message' => 'success']);
    }

    public function appointment()
    {
        $thisWeekMonday = date('Y-m-d',time()+( 1 - date('w'))*24*3600);
        $thisWeekFriday = date('Y-m-d',time()+( 5 - date('w'))*24*3600);

        return view('teachers.appointment', ['monday' => $thisWeekMonday, 'friday' => $thisWeekFriday]);
    }

    public function appointmentList(Request $request)
    {
        $request->request->add(['per_page' => $request->input('length')]);
	    $request->request->add(['page' => (intval($request->input('start')) / intval($request->input('length'))) + 1]);
        $data = Event::listAllEvents($request);

        return $data;
    }

    public function listAllSchedule(Request $request)
    {
        $request->request->add(['per_page' => $request->input('length')]);
	    $request->request->add(['page' => (intval($request->input('start')) / intval($request->input('length'))) + 1]);
        $data = Schedule::listAll($request);

        return $data;
    }
}
