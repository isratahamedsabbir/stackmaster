<?php

namespace App\Http\Controllers\Api;

use App\Events\TestNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\MobileNotification;
use App\Notifications\TestNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function test(){

        $user = auth('api')->user();
        
        $notiData = [
            'user_id' => $user->id,
            'admin_id' => 2,
            'title' => 'Confirm Schedule',
            'body' => 'Your Test Notification',
            'icon'  => env('APP_LOGO')
        ];

        User::find($user->id)->notify(new TestNotification($notiData));
        broadcast(new TestNotificationEvent($notiData))->toOthers();

        return true;
    }

    public function index()
    {
        try {
            $notifications = auth('api')->user()->unreadNotifications;
            return response()->json([
                'status'     => true,
                'message'    => 'All Notifications',
                'code'       => 200,
                'data'       => $notifications,
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back();
        }
    }
    public function readSingle($id)
    {
        try {
            $notification = auth('api')->user()->notifications()->find($id);
            if($notification) {
                $notification->markAsRead();
            }
            return response()->json([
                'status'     => true,
                'message'    => 'Single Notification',
                'code'       => 200,
                'data'       => $notification
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back();
        }
    }
    public function readAll()
    {
        try {
            auth('api')->user()->notifications->markAsRead();
            return response()->json([
                'status'     => true,
                'message'    => 'All Notifications Marked As Read',
                'code'       => 200,
                'data'       => null
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back();
        }
    }

}
