<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'schedule_id';
    protected $fillable = [
        'teacher_id',
        'date',
        'day',
        'start_date_time',
        'end_date_time',
        'duration',
        'status'
    ];

    public static function checking($date)
    {
        $data = self::where([
                ['date', 'LIKE', '%'.$date.'%'],
                ['teacher_id', '=', session('profile_id')]
        ])
        ->count();

        return $data;
    }

    public static function scheduleList($monday, $friday, $teacher_id, $status = null)
    {
        $con = [];

        if ($status == 'Active') {
            $con1 = ['schedules.status', '=', 'Active'];
            array_push($con, $con1);
        }
        array_push($con, ['schedules.teacher_id', '=', $teacher_id]);
        
        $data = self::where($con)
                ->whereBetween('schedules.date', [$monday, $friday])
                ->orderBy('schedules.date', 'asc')
                ->get();      
        return $data;
    }

    public static function listAll($request)
    {
        $columns = [
		    0 => 'date'
	    ];

	    $order = $columns[$request->input('order.0.column')];
	    $dir = $request->input('order.0.dir');
        $startDt = $request->input('search.value.date_from');
        $endDt = $request->input('search.value.date_to');

        $data = self::where([
                    ['teacher_id', '=', session('profile_id')]
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
