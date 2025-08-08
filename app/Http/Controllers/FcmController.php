<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

use Illuminate\Support\Facades\Http;

class FcmController extends Controller
{
    // public function updateDeviceToken(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'fcm_token' => 'required|string',
    //     ]);

    //     $request->user()->update(['fcm_token' => $request->fcm_token]);

    //     return response()->json(['message' => 'Device token updated successfully']);
    // }

    public function updateDeviceToken(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'fcm_token' => 'required|string',
    ]);

    // ابحث عن المستخدم حسب user_id المرسل
    $user = User::find($request->user_id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // حدث الـ fcm_token الخاص بالمستخدم
    $user->fcm_token = $request->fcm_token;
    $user->save();

    return response()->json(['message' => 'Device token updated successfully']);
}


    public function sendFcmNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $user = \App\Models\User::find($request->user_id);
        $fcm = $user->fcm_token;

        if (!$fcm) {
            return response()->json(['message' => 'User does not have a device token'], 400);
        }

        $title = $request->title;
        $description = $request->body;
        // $projectId = config('donation-system-b18e0'); # INSERT COPIED PROJECT ID
        $projectId = env('FCM_PROJECT_ID');


        // $credentialsFilePath = Storage::path('app/json/file.json');
        $credentialsFilePath = storage_path('app/json/donation-system-b18e0-firebase-adminsdk-fbsvc-b4698c6644.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => $title,
                    "body" => $description,
                ],
            ]
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    }
}