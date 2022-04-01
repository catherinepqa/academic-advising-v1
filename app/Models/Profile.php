<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class Profile extends Model
{
    use HasFactory;

    protected $primaryKey = 'profile_id';

    protected $fillable = [
        'profile_id',
        'user_id',
        'first_name',
        'last_name',
        'student_number',
        'employee_number',
        'address',
        'contact_number',
        'avatar'
    ];

    public static function list($role)
    {
        $data = self::select('profiles.*', 'users.email')
                ->join('users', 'users.id', 'profiles.user_id')
                ->where('users.role', '=', $role)
                ->get();

        return $data;
    }

    public static function getDetailsById($id)
    {
        $data = self::select('profiles.*', 'users.email', 'users.role')
        ->join('users', 'users.id', 'profiles.user_id')
        ->where('profiles.profile_id', '=', $id)
        ->first();

        return $data; 
    }

    public static function updateAvatar($request)
    {
        DB::beginTransaction();
		try {
			$img = $request->file('avatar');
			$dir = public_path().'/assets/images/user_avatars/'.$request->profile_id.'/';

			//Create Directory if not exist
			if (!file_exists($dir)) {
				mkdir($dir,0777,TRUE);
			}
            $filename = $img->getClientOriginalName();
			//$file = $request->file('file');
            $img->move(base_path('/public/assets/images/user_avatars/'.$request->profile_id.'/'), $filename);
			$data = [
				'avatar' => $filename,
			];

			DB::table('profiles')
			  ->where('profile_id', $request->profile_id)
			  ->update($data);
			DB::commit();

			return true;
		} catch (Exception $e) {

			DB::rollback();
			return $e->getMessage();
		}
    }

    public static function getAvatar($profile_id) 
    {
        $data = self::select('avatar')
            ->where('profile_id', '=', $profile_id)
            ->first();

        return $data->avatar;
    }
}
