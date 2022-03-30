<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatReply;
use App\Models\Event;
use App\Models\Messages;
use App\Models\Notification as ModelsNotification;
use App\Models\Profile;
use App\Models\User;
use App\Models\Zoom;
use App\Notifications\RegistrationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.students.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Profile $profile, User $user)
    {
        $msg = '';
        $password = Hash::make($request->student_number);
        $request->merge(['password' => $password, 'role' => 'student']);
        $data = $user::create($request->all());

        if ($data->exists) {
            $user = User::where('email', $request->email)->first();
            $request->merge(['user_id' => $user->id]);
            $profile::create($request->all());
            $dt = ['email' => $request->email, 'id' => $user->id, 'student_number' => $request->student_number];
            $msg = 'success';
            Notification::send($user, new RegistrationEmail($dt));
         } else {
            // failure 
         }

         return json_encode(['msg'=>$msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        $data = $profile::list('student');

        $array = json_decode( json_encode($data), true);
        return response()->json(['data' => $array]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
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
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile, User $user)
    {
        try {
            //Saving data to profiles table
            $profile = Profile::where('profile_id', $request->profile_id)->first();
            $profile->first_name        = $request->first_name;
            $profile->last_name         = $request->last_name;
            $profile->student_number    = $request->student_number;
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
     * @param  \App\Models\Student  $student
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

        return view('students.profile', ['data' => $data]);
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
        $data = ModelsNotification::getNotif(session('profile_id'));
        return view('students.notifications', ['data' => $data]);
    }

    public function calendar()
    { 
        $initial_date = date('Y-m-d');
        return view('students.calendar', ['initial_date' => $initial_date]);
    }

    public function eventList()
    {
        $data = Event::studentEventList();

	    return empty($data) ? [] : $data;
    }

    public function chat()
    {
        return view('students.chat');
    }

    public function chatList()
    {
        $data = Chat::chatList();

        return $data;
    }
    
    public function chatData(Request $request)
    {
        if ($request->ajax())
        {
            $data = Chat::chatData($request->parent_id);
            $replies = ChatReply::replies($request->parent_id);
            $parent_id = $request->parent_id;
            $receiver = Profile::getDetailsById($request->receiver_id);

            Chat::asRead($parent_id);
        }
    
        return view('students.chat_page', compact('data', 'receiver', 'parent_id', 'replies'))->render();
    }
}
