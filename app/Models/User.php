<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function verifyEmail($id)
    {
	    DB::beginTransaction();

	    try {
		    DB::table('users')
		      ->where('id', $id)
		      ->update(['email_verified_at' => date('Y-m-d H:i:s')]);
		    DB::commit();

		    return true;
	    } catch (Exception $e) {

		    DB::rollback();
		    return $e->getMessage();
	    }
    }

    public static function verifyDetails($data)
    {
	    DB::beginTransaction();

	    try {
		    $arr_data = '';
		    if (Auth::attempt($data)) {
			    $user = Auth::user();

			    if (empty($user->email_verified_at)) {
				    $arr_data = 'not activated';
			    } else {
				    $arr_data = [
					    'success'       => true,
					    'id'            => $user->id,
					    'email'         => $user->email,
					    'updated_at'    => $user->updated_at,
					    'role'          => $user->role
				    ];
					self::setSession($user->id, $user->role);
			    }

			    return $arr_data;
		    }
	    } catch (Exception $e) {

		    DB::rollback();
		    return $e->getMessage();
	    }
    }

    public static function setSession($user_id, $role)
    {
        $user_data = Profile::where('user_id', '=', $user_id)->first();
        session(['profile_id' => $user_data->profile_id]);
        session(['user_id' => $user_data->user_id]);
        session(['full_name'  => $user_data->first_name.' '.$user_data->last_name]);
        session(['first_name' => $user_data->first_name]);
        session(['role'       => $role]);
        session(['avatar'     => $user_data->avatar]);
    }
}
