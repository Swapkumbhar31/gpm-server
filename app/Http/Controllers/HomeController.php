<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chapter = DB::table('chapters')->where('id',Auth::user()->chapter_id)->first();
        $video = null;
        $image = null;
        if ($chapter !== null){
            $video = DB::table('media')->where('filename',$chapter->video_id)->first();
            $image = DB::table('media')->where('filename',$chapter->pic)->first();
        }
        return view('home')->with([
            'video' => $video,
            'image' => $image,
            'chapter' => $chapter,
        ]);
    }
}
