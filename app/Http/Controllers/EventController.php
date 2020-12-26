<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Resources\Event as EventResource;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function calender()
    {
        $events = Event::latest()->get();
        return view('admin/calender/index')->with([
            'page_title' => 'Calender',
            'events' => $events,
        ]);
    }
    public function usercalender()
    {
        $events = Event::latest()->get();
        return view('user/calender/index')->with([
            'page_title' => 'Calender',
            'events' => $events,
        ]);
    }
    public function index()
    {
        $media = Event::latest()->paginate(15);
        return view('admin/event/index')->with(
            'events',
            EventResource::collection($media)
        );
    }

    public function create()
    {
        return view('admin.event.add');
    }

    public function edit(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        return view('admin.event.edit')->with(
            'event',
            $event
        );
    }

    public function getAllEvents()
    {
        $events = Event::orderBy('held_on')->get();
        return EventResource::collection($events);
    }
    public function getNextEvents($count = 10)
    {
        $events = Event::orderBy('held_on')->where('held_on', '>=', NOW())->paginate($count);
        return EventResource::collection($events);
    }
    public function getEvent($id)
    {
        $event = Event::findOrFail($id);
        return new EventResource($event);
    }

    public function destroy(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        if ($event->delete()) {
            if ($request->wantsJson()) {
                return new EventResource($event);
            } else {
                return redirect(route('event'));
            }
        }
    }

    public function store(Request $request)
    {
        $event = $request->isMethod('put') ? Event::findOrFail($request->id) : new Event;
        $date=date_create($request->input('date') . " "  . $request->input('time'));
        $event->id = $request->input('id');
        $event->event_name = $request->input('event_name');
        $event->description = $request->input('description');
        $event->type = $request->input('type');
        $event->held_on = $date;
        $event->speakers = json_encode(explode(",", $request->input('speakers')));
        $event->topics = json_encode(explode(",", $request->input('topics')));
        $request->request->add(
             ['message' =>
                  'Dear Member, a '.$event->type .' '.
                  $event->event_name .' by '.$request->input('speakers').
                  ' has been scheduled on '. date_format($event->held_on,"dS F Y") .
                  ' @ '. date_format($event->held_on,"h:i A") .'.']);
        if ($event->save()) {
            app('App\Http\Controllers\NotificationController')->addNotifications($request);
            if ($request->wantsJson()) {
                return new EventResource($event);
            } else {
                return redirect(route('event'));
            }
        }
    }
    public function update(Request $request)
    {
        $event = Event::findOrFail($request->id);
        $date=date_create($request->input('date') . " "  . $request->input('time'));
        $event->id = $request->input('id');
        $event->event_name = $request->input('event_name');
        $event->type = $request->input('type');
        $event->description = $request->input('description');
        $event->held_on = $date;
        $event->speakers = json_encode(explode(",", $request->input('speakers')));
        $event->topics = json_encode(explode(",", $request->input('topics')));
        if ($event->save()) {
            if ($request->wantsJson()) {
                return new EventResource($event);
            } else {
                return redirect(route('event'));
            }
        }
    }
}
