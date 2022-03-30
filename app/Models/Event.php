<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';
    protected $fillable = [
        'event_id',
        'teacher_id',
        'student_id',
        'schedule_id',
        'title',
        'zoom_meeting_id',
        'zoom_link',
        'status'
    ];

    public static function eventList()
    {
		$data = self::select('events.*',
                            'schedules.start_date_time as start_date',
                            'schedules.end_date_time as end_date',
                        )
                        ->join('schedules', 'schedules.schedule_id', 'events.schedule_id')
                        ->where([
                            ['events.teacher_id', '=', session('profile_id')],
                            ['events.status', '=', 'Approved']
                        ])
                        ->get();

        $arr = [];
        
        foreach($data as $event) {
            $d = [
                    'title' => $event->title,
                    'start' => date('Y-m-d', strtotime($event->start_date)),
                    'end' => date('Y-m-d', strtotime($event->end_date)),
                    'id' => $event->zoom_link,
                    'textColor' => $event->event_id
                ];
            array_push($arr, $d);    
        }

		return empty($data) ? null : json_encode($arr);
    }

    public static function studentEventList()
    {
		$data = self::select('events.*', 
                            DB::raw('CONCAT(profiles.first_name," ", profiles.last_name) AS teacher_name'),
                            'schedules.start_date_time as start_date',
                            'schedules.end_date_time as end_date',
                        )
                        ->join('profiles', 'profiles.profile_id', 'events.teacher_id')
                        ->join('schedules', 'schedules.schedule_id', 'events.schedule_id')
                        ->where([
                            ['student_id', '=', session('profile_id')],
                            ['events.status', '=', 'Approved']
                        ])
                        ->get();

        $arr = [];
    
        foreach($data as $event) {
            $stringStartDate = date('l jS \of F Y h:i A', strtotime($event->start_date));
            $stringEndDate =  date('h:i A', strtotime($event->end_date));
            $d = [
                    'title' => 'You booked an appointment to '.$event->teacher_name.' on '.$stringStartDate . ' to ' .$stringEndDate,
                    'start' => date('Y-m-d', strtotime($event->start_date)),
                    'end' => date('Y-m-d', strtotime($event->end_date)),
                    'id' => $event->zoom_link,
                    'textColor' => $event->event_id
                ];
            array_push($arr, $d);    
        }

		return empty($data) ? null : json_encode($arr);
    }

    public static function listAllEvents($request)
    {
        $columns = [
		    0 => 'student_name',
		    1 => 'teacher_name',
            2 => 'start_date'
	    ];

	    $order = $columns[$request->input('order.0.column')];
	    $dir = $request->input('order.0.dir');
        $startDt = $request->input('search.value.date_from');
        $endDt = $request->input('search.value.date_to');

        $data = self::select(
                    'events.*', 
                    'schedules.date as start_date',
                    DB::raw('CONCAT(profiles.first_name," ", profiles.last_name) AS teacher_name'),
                    DB::raw('CONCAT(p.first_name," ", p.last_name) AS student_name')
                )
                ->join('profiles', 'profiles.profile_id', 'events.teacher_id')
                ->join('profiles as p', 'p.profile_id', 'events.student_id')
                ->join('schedules', 'schedules.schedule_id', 'events.schedule_id')
                ->whereBetween('schedules.date', [$startDt, $endDt])
                ->orderBy($order,$dir)
                ->paginate($request->per_page?$request->per_page:$page);

        $json_data = array(
		    "draw"            => intval($request->input('draw')),
		    "recordsTotal"    => intval($data->total()),
		    "recordsFiltered" => intval($data->total()),
		    "data"            => $data->items(),
		    "start"           => intval($request->input('start'))
	    );

	    return $json_data;
    }

    public static function teacherListAllEvents($request)
    {
        $columns = [
		    0 => 'student_name'
	    ];

	    $order = $columns[$request->input('order.0.column')];
	    $dir = $request->input('order.0.dir');
        $startDt = $request->input('search.value.date_from');
        $endDt = $request->input('search.value.date_to');

        $data = self::select(
                    'events.*', 
                    'schedules.date as start_date',
                    DB::raw('CONCAT(profiles.first_name," ", profiles.last_name) AS student_name')
                )
                ->join('profiles', 'profiles.profile_id', 'events.student_id')
                ->join('schedules', 'schedules.schedule_id', 'events.schedule_id')
                ->where([
                    ['events.teacher_id', '=', session('profile_id')]
                ])
                ->whereBetween('schedules.date', [$startDt, $endDt])
                ->orderBy($order,$dir)
                ->paginate($request->per_page?$request->per_page:$page);

        $json_data = array(
		    "draw"            => intval($request->input('draw')),
		    "recordsTotal"    => intval($data->total()),
		    "recordsFiltered" => intval($data->total()),
		    "data"            => $data->items(),
		    "start"           => intval($request->input('start'))
	    );

	    return $json_data;
    }
}
