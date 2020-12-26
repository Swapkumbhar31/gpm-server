<?php

namespace App\Http\Controllers;

use App\Media;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Resources\Media as MediaResource;

class MediaController extends Controller
{
    public function __construct()
    {
          $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media = Media::paginate(15);
        return view('admin.media.index')->with(
            'media', MediaResource::collection($media)
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.media.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->file('video_url') != null){
            $uuid = str_replace("-","",Uuid::generate()->string);
            $id = $uuid.".".$request->file('video_url')->getClientOriginalExtension();
            $destinationPath = 'uploads/';
            $disk = Storage::disk('local');
            if ($disk->put($destinationPath.$id, fopen($request->file('video_url'), 'r+'))){
                $media = new Media;
                $media->filename = $uuid;
                $media->size = ''.$request->file('video_url')->getSize();
                $media->extention = $request->file('video_url')->getClientOriginalExtension();
                $media->mimetype = $request->file('video_url')->getMimeType();
                if (strlen($request->file('video_url')->getClientOriginalName()) > 100)
                {
                    $media->originalFileName = substr($request->file('video_url')->getClientOriginalName(), 0,97).'...';
                }
                else
                {
                    $media->originalFileName = $request->file('video_url')->getClientOriginalName();
                }
                $media->save();
            }
            return response("File stored successfully", 200);
        }else{
            return response("Invalid request URI", 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media = Media::where('filename', $id)->first();
        if(file_exists(storage_path('app/uploads/'.$id.'.'.$media->extention))){
            unlink(storage_path('app/uploads/'.$id.'.'.$media->extention));
        }
        $media->delete();
        return redirect()->back();
    }
}
