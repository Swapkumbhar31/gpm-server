<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;
use App\Http\Resources\Activity as ActivityResource;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request, $user_id, $log, $type)
    {
        $activity = new Activity;
        $activity->user_id = $user_id;
        $activity->log = $log;
        $activity->type = $type;
        $activity->save();
        return true;
    }

    public function getAllActivitiesByUser(Request $request) {
        $activities = Activity::where('user_id', $request->user_id)->latest()->paginate(15);
        return ActivityResource::collection($activities);
    }

    public function getAllEarningByUser(Request $request) {
        $activities = Activity::where('user_id', $request->user_id)->where('type','earning')->latest()->paginate(15);
        return ActivityResource::collection($activities);
    }

    public function getAllActivities() {
        $activities = Activity::latest()->where('type','earning')->where('type','referral')->paginate(100);
        return ActivityResource::collection($activities);
    }
}
