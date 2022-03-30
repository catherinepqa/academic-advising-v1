<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Profile;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class DashoardController extends Controller
{
    public function index()
    {
        $arr = [];
        $thisWeekMonday = date('Y-m-d',time()+( 1 - date('w'))*24*3600);
        $thisWeekFriday = date('Y-m-d',time()+( 6 - date('w'))*24*3600);
        for ($i=1; $i < 6; $i++)
        {
            $date = date('Y-m-d',time()+( $i - date('w'))*24*3600);
            $d = ['date' => $date, 'day' => date('l', strtotime($date))];
            array_push($arr, $d);
        }

        $teachers = Profile::list('teacher'); 
        return view('students.dashboard', ['teachers' => $teachers, 'dates' => $arr, 'monday' => $thisWeekMonday, 'friday' => $thisWeekFriday]);
    }

    public function adminDashboard()
    {
        $teachers = User::where('role', 'teacher')->count();
        $student = User::where('role', 'student')->count();
        $events = Event::where('status', 'Approved')->count();
        $thisWeekMonday = date('Y-m-d',time()+( 1 - date('w'))*24*3600);
        $thisWeekFriday = date('Y-m-d',time()+( 5 - date('w'))*24*3600);
    
        return view('admin.dashboard.index', 
            ['teachers' => $teachers, 'students' => $student, 'events' => $events, 
            'monday' => $thisWeekMonday, 'friday' => $thisWeekFriday]
        );
    }

    public function teacherDashboard()
    {
        $thisWeekMonday = date('Y-m-d',time()+( 1 - date('w'))*24*3600);
        $thisWeekFriday = date('Y-m-d',time()+( 6 - date('w'))*24*3600);
        $list = Schedule::scheduleList($thisWeekMonday, $thisWeekFriday, session('profile_id'));
        $initial_date = date('Y-m-d');
        return view('teachers.dashboard', ['initial_date' => $initial_date, 'schedule_cnt' => count($list)]);
    }
}
