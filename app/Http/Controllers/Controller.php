<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Profile;
use App\Models\Student;
use App\Models\User;
use App\Notifications\ForgotPassword;
use App\Notifications\RegistrationEmail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function logIn()
    {
        return view('students.login');
    }

    public function teacherLogIn()
    {
        return view('teachers.login');
    }

    public function loginProcess(Request $request)
    {
	    $user_data = array(
		    'email'  => $request->get('email'),
		    'password' => $request->get('password')
	    );

	    $data = User::verifyDetails($user_data);

	    if (!empty($data)) {
		    if ($data == 'not activated') {
			    Session::flash('message', 'Please verify your email address.');
			    Session::flash('alert-class', 'alert-danger');

			    return redirect()->route('studentLogIn');
		    } else {
				return redirect()->route('studentDashboard');
		    }
	    } else {
		    Session::flash('message', 'Login credentials does not exist. Please click sign up');
		    Session::flash('alert-class', 'alert-danger');

		    return redirect()->route('studentLogIn');
	    }
    }

    public function teacherLoginProcess(Request $request)
    {
	    $user_data = array(
		    'email'  => $request->get('email'),
		    'password' => $request->get('password')
	    );

	    $data = User::verifyDetails($user_data);

	    if (!empty($data)) {
		    if ($data == 'not activated') {
			    Session::flash('message', 'Please verify your email address.');
			    Session::flash('alert-class', 'alert-danger');

			    return redirect()->route('teacherLogIn');
		    } else {
				return redirect()->route('teacherDashboard');
		    }
	    } else {
		    Session::flash('message', 'Login credentials does not exist. Please click sign up');
		    Session::flash('alert-class', 'alert-danger');

		    return redirect()->route('teacherLogIn');
	    }
    }

    public function signUp()
    {
        return view('students.signup');
    }

    public function signUpProcess(Request $request, Profile $profile, User $user)
    {
        $msg = '';
        $password = Hash::make($request->password);
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

    public function verifyEmail($id)
    {
	    $update = User::verifyEmail($id);

		if ($update == 1) {
			return redirect('student');
		}
    }

    public function logout(Request $request)
	{
        $route = '';
        if (session('role') == 'student') {
            $route = 'student';
        } else if (session('role') == 'teacher') {
            $route = 'teacher';
        } else {
            $route = '/';
        }
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect($route);
	}

    public function adminLogIn()
    {
        return view('admin.login');
    }

    public function changePassword(Request $request)
    {
        $password = Hash::make($request->new_password);

        $user = User::find($request->user_id);
        $user->password     = $password;
        $user->updated_at   = date('Y-m-d H:i:s');
        $user->save();

        return response()->json(['message' => 'success']);
    }

    public function adminLoginProcess(Request $request)
    {
        $user_data = array(
		    'email'  => $request->get('email'),
		    'password' => $request->get('password')
	    );

	    if (Auth::attempt($user_data)) {
            $user = Auth::user();
            session(['user_id' => $user->user_id]);
            session(['role'    => $user->role]);
            return redirect()->route('adminDashboard');
        } else {
            return redirect()->route('adminLogIn');
        }
    }

    public function forgotPass()
    {
       return view('admin.forgot_password');
    }

    public function forgotPassProcess(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $dt = ['email' => $request->email, 'id' => $user->id];
        $msg = 'success';
        Notification::send($user, new ForgotPassword($dt));
        return json_encode(['msg'=>$msg]);
    }

    public function resetPass($id)
    {
        return view('admin.reset_password', ['user_id' => $id]);
    }
}
