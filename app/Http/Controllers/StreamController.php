<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stream;
use Auth;
use Uuid;
use App\Http\Resources\Stream as StreamResource;

class StreamController extends Controller
{
    //

    public function livestream()
    {
        $streams = Stream::where('isLive','1')->get();
        return view('user.livestream')->with([
            'streams' => $streams,
        ]);
    }
    public function startStreaming(Request $resquest)
    {
        $stream = new Stream;
        $stream->topic_name = $resquest->topic_name === null ? "Default wala naam" : $resquest->topic_name;
        $stream->description = $resquest->description === null ? "Default wala description" : $resquest->description;
        $stream->started_by = Auth::user() === null ? "no user" : Auth::user()->id;
        $stream->stream_uuid = Uuid::generate()->string;
        if($stream->save()){
            $result = array([
                'status'=>200,
                'data'=> new StreamResource($stream)
            ]);
            return response(json_encode($result));
        }else{
            $result = array([
                'status'=>0,
                'error'=>'Error occured'
            ]);
            return response(json_encode($result));
        }

    }

    public function liveSuccessful(Request $request)
    {
        $stream = Stream::where('stream_uuid',$request->id)->first();
        $stream->isLive = 1;
        if($stream->save()){
            $result = array([
                'status'=>200,
                'data'=> new StreamResource($stream)
            ]);
            return response(json_encode($result));
        }else{
            $result = array([
                'status'=>0,
                'error'=>'Error occured'
            ]);
            return response(json_encode($result));
        }
    }

    public function stopLiveStream(Request $request)
    {
        $stream = Stream::where('stream_uuid',$request->id)->first();
        $stream->isLive = 0;
        if($stream->save()){
            $result = array([
                'status'=>200,
                'data'=>new StreamResource($stream)
            ]);
            return response(json_encode($result));
        }else{
            $result = array([
                'status'=>0,
                'error'=>'Error occured'
            ]);
            return response(json_encode($result));
        }
    }
}
