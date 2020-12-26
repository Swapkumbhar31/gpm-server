<?php

namespace App\Http\Controllers;

use App\Notification;
use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Notification as NotificationResource;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->latest()->get();
        return view('user.notifications.index')->with([
            'notifications' => $notifications
        ]);
    }

    public function addNotifications(Request $request)
    {
        $users = null;
        switch ($request->audience) {
            case '1':
                $users = User::get();
                break;
            case '2':
                $users = User::where('membership', 'core')->get();
                break;
            case '3':
                $users = User::where('membership', 'basic')->get();
                break;
            case '4':
                $users = User::where('membership', 'core')->where('isAdmin', '2')->get();
                break;
            default:
                return response('Inavlid audience type', 500);
                break;
        }
        foreach ($users as $user) {
            $this->store($request->message, $user->id);
        }
        return redirect('/notifications');
    }


    public function store($message, $user_id)
    {
        $notification = new Notification;
        $notification->message = $message;
        $notification->user_id = $user_id;
        $notification->save();
        return new NotificationResource($notification);
    }

    public function get(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user_id)->latest()->get();
        return NotificationResource::collection($notifications);
    }

    public function readNotification($id)
    {
        $notification = Notification::find($id);
        $notification->is_read = 1;
        $notification->save();
        return new NotificationResource($notification);
    }

    public function deleteAll(Request $request)
    {
        Notification::where('user_id', $request->user_id)->delete();
        $result = array();
        $result['message'] = "All notification deleted";
        $result['status'] = 200;
        return json_encode($result);
    }

    public function destroy($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->delete();
            return new NotificationResource($notification);
        }   else {
            return response("Notification not found", 404);
        }
    }

    public function delete($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->delete();
            return redirect()->back();
        }   else {
            return response("Resource not found", 404);
        }
    }

    public function send(Request $request)
    {
        return view('admin.notification.send');
    }
}
