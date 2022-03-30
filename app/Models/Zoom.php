<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Request;

class Zoom extends Model
{
    use HasFactory;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function generateZoomToken()
    {
        $key = env('ZOOM_API_KEY', '');
        $secret = env('ZOOM_API_SECRET', '');
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];
        return JWT::encode($payload, $secret, 'HS256');
    }

    public function retrieveZoomUrl()
    {
        return env('ZOOM_API_URL', '');
    }

    public function zoomRequest()
    {
        $jwt = self::generateZoomToken();
        return \Illuminate\Support\Facades\Http::withHeaders([
            'authorization' => 'Bearer ' . $jwt,
            'content-type' => 'application/json',
        ]);
    }

    public function zoomGet(string $path, array $query = [])
    {
        $url = self::retrieveZoomUrl();
        $request = self::zoomRequest();
        return $request->get($url . $path, $query);
    }

    public function zoomPost(string $path, array $body = [])
    {
        $url = self::retrieveZoomUrl();
        $request = self::zoomRequest();
        return $request->post($url . $path, $body);
    }

    public function zoomPatch(string $path, array $body = [])
    {
        $url = self::retrieveZoomUrl();
        $request = self::zoomRequest();
        return $request->patch($url . $path, $body);
    }

    public function zoomDelete(string $path, array $body = [])
    {
        $url = self::retrieveZoomUrl();
        $request = self::zoomRequest();
        return $request->delete($url . $path, $body);
    }

    public static function zoomCreate($request)
    {
        $path = 'users/me/meetings';
        $response = self::zoomPost($path, [
            'topic' => $request->topic,
            'type' => self::MEETING_TYPE_SCHEDULE,
            //'start_time' => '2022-03-22T10:00:00',
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'agenda' => $request->agenda,
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                //'waiting_room' => true,
				"join_before_host" => true
            ],
        ]);

        return [
            'success' => $response->status() === 201,
            'data' => json_decode($response->body(), true),
        ];
    }

    public static function deleteZoom(string $id)
    {
        $path = 'meetings/' . $id;
        $response = self::zoomDelete($path);

        return [
            'success' => $response->status() === 204,
            'data' => json_decode($response->body(), true),
        ];
    }
}
