<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashoardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeachersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin Module Routes
Route::get('/', [Controller::class, 'adminLogIn'])->name('adminLogIn');
Route::post('/admin_login_process', [Controller::class, 'adminLoginProcess'])->name('adminLoginProcess');
Route::prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashoardController::class, 'adminDashboard'])->name('adminDashboard');

        // Teachers module routes
        Route::get('/teachers', [TeachersController::class, 'index'])->name('teacherList');
        Route::post('/teachers/add', [TeachersController::class, 'store'])->name('teacherAdd');
        Route::get('/teachers/list', [TeachersController::class, 'show'])->name('teacherDataList');
        Route::get('/teachers/details', [TeachersController::class, 'edit'])->name('teacherDetails');
        Route::put('/teachers/update', [TeachersController::class, 'update'])->name('teacherUpdateData');
        Route::delete('/teachers/delete', [TeachersController::class, 'destroy'])->name('teacherDeleteData');

        // Students module routes
        Route::get('/students', [StudentController::class, 'index'])->name('studentList');
        Route::get('/students/list', [StudentController::class, 'show'])->name('studentDataList');
        Route::post('/students/add', [StudentController::class, 'store'])->name('studentAdd');
        Route::get('/students/details', [StudentController::class, 'edit'])->name('studentDetails');
        Route::put('/students/update', [StudentController::class, 'update'])->name('studentUpdateData');
        Route::delete('/students/delete', [StudentController::class, 'destroy'])->name('studentDeleteData');
    });
});

// Student Module Routes
Route::prefix('student')->group(function () {
    Route::get('/', [Controller::class, 'logIn'])->name('studentLogIn');
    Route::post('/login_process', [Controller::class, 'loginProcess'])->name('loginProcess');
    Route::get('/signup', [Controller::class, 'signUp'])->name('signUp');
    Route::post('/sign_up_process', [Controller::class, 'signUpProcess'])->name('signUpProcess');
    Route::get('/verify_email/{id}', [Controller::class, 'verifyEmail'])->name('verify_email');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashoardController::class, 'index'])->name('studentDashboard');
        Route::post('/appointment', [EventController::class, 'create'])->name('appointment');
        Route::get('/profile', [StudentController::class, 'profile'])->name('studentProfile');
        Route::post('/profile/update', [StudentController::class, 'updateProfile'])->name('studentProfileUpdate');
        Route::get('/notifications', [StudentController::class, 'notifications'])->name('studentNotifications');
        Route::get('/calendar', [StudentController::class, 'calendar'])->name('studentCalendar');
        Route::get('/event/list', [StudentController::class, 'eventList'])->name('studentEventList');
        Route::get('/chat', [StudentController::class, 'chat'])->name('studentChat');
        Route::get('/chat/list', [StudentController::class, 'chatList'])->name('studentChatList');
        Route::get('/chat/fetch', [StudentController::class, 'chatData'])->name('studentChatData');
    });
});

// Teacher Module Routes
Route::prefix('teacher')->group(function () {
    Route::get('/', [Controller::class, 'teacherLogIn'])->name('teacherLogIn');
    Route::post('/login_process', [Controller::class, 'teacherLoginProcess'])->name('teacherLoginProcess');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashoardController::class, 'teacherDashboard'])->name('teacherDashboard');
        Route::get('/profile', [TeachersController::class, 'profile'])->name('teacherProfile');
        Route::post('/profile/update', [TeachersController::class, 'updateProfile'])->name('teacherProfileUpdate');
        Route::get('/notifications', [TeachersController::class, 'notifications'])->name('teacherNotifications');
        Route::post('/notifications/accept', [TeachersController::class, 'acceptEvent'])->name('teacherNotificationAccept');
        Route::get('/event/list', [TeachersController::class, 'eventList'])->name('teacherEventList');
        Route::get('/chat', [TeachersController::class, 'chat'])->name('teacherChat');
        Route::get('/chat/list', [TeachersController::class, 'chatList'])->name('teacherChatList');
        Route::get('/chat/fetch', [TeachersController::class, 'chatData'])->name('teacherChatData');
        Route::get('/schedule', [TeachersController::class, 'schedule'])->name('teacherSchedule');
        Route::post('/addTime', [TeachersController::class, 'addTime'])->name('addTime');
        Route::get('/schedule/list', [TeachersController::class, 'scheduleList'])->name('teacherScheduleList');
        Route::delete('/schedule/delete', [TeachersController::class, 'destroySchedule'])->name('destroySchedule');
        Route::get('/appointment', [TeachersController::class, 'appointment'])->name('teacherAppointment');
        Route::get('/appointment/list', [TeachersController::class, 'appointmentList'])->name('appointmentList');
        Route::get('/schedule/listAll', [TeachersController::class, 'listAllSchedule'])->name('listAllSchedule');
    });
});

//Logout
Route::post('/logout', [Controller::class, 'logout'])->name('logout');

//Notifications
Route::get('/notif-count', [NotificationController::class, 'count'])->name('notifCount');
Route::get('/notif-get', [NotificationController::class, 'getNotif'])->name('getNotif');
Route::delete('/notif-delete', [NotificationController::class, 'destroy'])->name('notifDelete');
Route::put('/notif-read', [NotificationController::class, 'read'])->name('notifRead');
Route::delete('/notif-read', [NotificationController::class, 'deleteAll'])->name('notifDeleteAll');

//Chat
Route::post('/send', [ChatController::class, 'send'])->name('send');
Route::post('/reply', [ChatController::class, 'reply'])->name('reply');
Route::post('/student-reply', [ChatController::class, 'studentReply'])->name('studentReply');
Route::get('/chat-count', [ChatController::class, 'chatCount'])->name('chatCount');
Route::post('/chat/delete', [ChatController::class, 'chatDelete'])->name('chatDelete');

//Event
Route::post('/event/closed', [EventController::class, 'closedEvent'])->name('closedEvent');
Route::get('/event/listAll', [EventController::class, 'listAllEvents'])->name('listAllEvents');

//Change Password
Route::post('/change-password', [Controller::class, 'changePassword'])->name('changePassword');

//Forgot Password
Route::get('/forgot-password', [Controller::class, 'forgotPass'])->name('forgotPass');
Route::post('/forgot-password/process', [Controller::class, 'forgotPassProcess'])->name('forgotPassProcess');
Route::get('/reset-password/{id}', [Controller::class, 'resetPass'])->name('resetPass');