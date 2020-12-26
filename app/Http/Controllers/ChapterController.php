<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Http\Resources\Chapter as ChapterResource;
use App\Media;
use App\Module;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ChapterController extends Controller
{

    public function index($module_id)
    {
        $chapters = Chapter::where("module_id", $module_id)->orderBy('chap_index')->paginate(10);
        return view('admin.chapter.index')->with(
            'chapters', ChapterResource::collection($chapters)
        );
    }

    public function view($id)
    {
          return view('user.video')->with([
               'video_id' => $id
          ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit($chapter_id)
    {
        $chapter = Chapter::findOrFail($chapter_id);
        $imagedb = Media::where('mimetype', 'like', 'image%')->get();
        $images = array();
        foreach ($imagedb as $image) {
            $images[$image->filename] = $image->originalFileName;
        }
        $videosdb = Media::where('mimetype', 'like', 'video%')->get();
        $videos = array();
        foreach ($videosdb as $video) {
            $videos[$video->filename] = $video->originalFileName;
        }
        return view('admin.chapter.edit')->with([
            'chapter' => $chapter,
            'images' => $images,
            'videos' => $videos,
        ]);
    }

    public function show($id)
    {
        $chapter = Chapter::findOrFail($id);
        $questions = DB::table('questions')->where('chapter_id', $chapter->id)->get();
        $image = DB::table('media')->where('filename', $chapter->pic)->first();
        if ($image !== null) {
            $image_path = '/chapters/' . $chapter->pic . '.' . DB::table('media')->where('filename', $chapter->pic)->first()->extention;
        } else {
            $image_path = '/chapters/null';
        }
        return view('admin.chapter.view')->with([
            'chapter' => new ChapterResource($chapter),
            'questions' => $questions,
            'image_path' => $image_path,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $module_id = explode("/", URL::previous());
        $module_id = $module_id[count($module_id) - 1];
        if (!is_numeric($module_id)) {
            return redirect(route('modules'));
        }
        $imagedb = Media::where('mimetype', 'like', 'image%')->get();
        $images = array();
        foreach ($imagedb as $image) {
            $images[$image->filename] = $image->originalFileName;
        }
        $videosdb = Media::where('mimetype', 'like', 'video%')->get();
        $videos = array();
        foreach ($videosdb as $video) {
            $videos[$video->filename] = $video->originalFileName;
        }
        return view('admin.chapter.add')->with([
            'images' => $images,
            'videos' => $videos,
            'module_id' => $module_id,
        ]);
    }

    public function store(Request $request)
    {
        $chapter = new Chapter;
        $chapter->name = $request->input('name');
        $chapter->description = $request->input('description');
        $chapter->video_id = $request->input('video_id');
        $chapter->chap_index = $request->input('chap_index');
        $chapter->module_id = $request->input('module_id');
        $chapter->pic = $request->input('image_id');
        if ($chapter->save()) {
            return redirect(route('chapter', $chapter->module_id));
        }
    }

    public function update(Request $request)
    {
        $chapter = Chapter::findOrFail($request->id);
        $chapter->name = $request->input('name');
        $chapter->description = $request->input('description');
        $chapter->video_id = $request->input('video_id');
        $chapter->chap_index = $request->input('chap_index');
        $chapter->module_id = $request->input('module_id');
        $chapter->pic = $request->input('image_id');
        $chapter->mobile_video_id = $request->input('mobile_video_id');
        if ($chapter->save()) {
            return redirect(route('chapter', $chapter->module_id));
        }
    }

    public function destroy($id)
    {
        $chapter = Chapter::findOrFail($id);
        $questions = DB::table('questions')->where('chapter_id', $chapter->id)->delete();
        if ($chapter->delete()) {
            return redirect(route('chapter', $chapter->module_id));
        }
    }

    public function chapter(Request $request)
    {
        $chapter = Chapter::with('image', 'video', 'module')->findOrFail(User::findOrFail($request->user_id)->chapter_id);
        if ($request->wantsJson()) {
            return new ChapterResource($chapter);
        }
    }

    public function nextChapter($student_id)
    {
        $chapter_id = User::findOrFail($student_id)->chapter_id;
        $module_id = Chapter::findOrFail($chapter_id)->module_id;
        $chapter_index = Chapter::findOrFail($chapter_id)->chap_index;
        $chapter = Chapter::where('module_id', $module_id)->where('chap_index', '>', $chapter_index)->orderBy('chap_index')->paginate(3);
        return new ChapterResource($chapter);
    }

    public function viewVideo(Request $request, $id = null)
    {
        if ($id) {
            if ($request->wantsJson()) {
                $user = User::where('api_key', $request->api_key)->first();
            } else {
                $user = Auth::user();
            }
            // Current state
            $currentChapter = DB::table('chapters')->where('id', $user->chapter_id)->first();
            $currentModule = DB::table('modules')->where('id', $currentChapter->module_id)->first();
            // User wants
            $chapter = Chapter::with('image', 'video', 'module', 'questionCount')->find($id);
            $module = Module::find($chapter->module_id);
            if ($module->mod_index === $currentModule->mod_index) {
                $statusCheck = Chapter::where('chap_index', '<', $chapter->chap_index)->where('module_id', $module->id)->count();
                $statusCheck2 = Chapter::where('chap_index', '<', $currentChapter->chap_index)->where('module_id', $module->id)->count();

                if(($statusCheck - $statusCheck2) === 0) {
                    $chapter['isFinised'] = true;
                    $chapter['ongoing'] = true;
                    $chapter['nextChapter'] =  null;
                } elseif(($statusCheck - $statusCheck2) > 0) {
                    $chapter['isFinised'] = false;
                    $chapter['nextChapter'] = Chapter::where('chap_index', '>', $chapter->chap_index)->where('module_id', $module->id)->orderBy('chap_index')->first();
                    $chapter['ongoing'] = false;
                } else {
                    $chapter['isFinised'] = true;
                    $chapter['nextChapter'] = Chapter::where('chap_index', '>', $chapter->chap_index)->where('module_id', $module->id)->orderBy('chap_index')->first();
                    $chapter['ongoing'] = true;
                }
            } else if ($module->mod_index < $currentModule->mod_index) {
                $chapter['isFinised'] = true;
                $chapter['ongoing'] = false;
                $chapter['nextChapter'] = Chapter::where('chap_index', '>', $chapter->chap_index)->where('module_id', $module->id)->orderBy('chap_index')->first();
            } else {
                $chapter['isFinised'] = false;
                return response('The request has not been applied because it lacks valid authentication credentials for the target resource.', 403);;
            }
            if ($request->wantsJson()) {
                return json_encode($chapter);
            }
            if ($chapter === null) {
                return redirect(route('syllabus'));
            }
        } else {
            $chapter = DB::table('chapters')->where('id', Auth::user()->chapter_id)->first();
        }
        $video = DB::table('media')->where('filename', $chapter->video_id)->first();
        $image = DB::table('media')->where('filename', $chapter->pic)->first();
        return view('user.viewVideo')->with([
            'video' => $video,
            'image' => $image,
            'chapter' => $chapter,
        ]);
    }

    public function chapterProgress($student_id)
    {
        $chapter_id = User::findOrFail($student_id)->chapter_id;
        $module_id = Chapter::findOrFail($chapter_id)->module_id;
        $chapter_index = Chapter::findOrFail($chapter_id)->chap_index;
        $total = count(Chapter::where('module_id', $module_id)->get());
        $completed = count(Chapter::where('module_id', $module_id)->where('chap_index', '<', $chapter_index)->get());
        $result['chapters'] = number_format($completed * 100 / $total, 2);
        $result['completed'] = $completed;
        $result['total'] = $total;
        return json_encode($result);
    }

    public function moduleProgress(Request $request)
    {
        $chapter_id = User::where('id', $request->user_id)->first()->chapter_id;
        $chapter_index = Chapter::findOrFail($chapter_id)->chap_index;
        $module_id = Chapter::where('id', $chapter_id)->first()->module_id;
        $allModules = Module::orderBy('mod_index')->get();
        $result = array();
        foreach ($allModules as $module) {
            $temp = array();
            $temp['module_name'] = $module['name'];
            $temp['chap_count'] = Chapter::where('module_id', $module['id'])->count();
            if (intval($module_id) === intval($module['id'])) {
                $temp['state'] = 'ongoing';
                $chapters = ChapterResource::collection(Chapter::where('module_id', $module['id'])
                        ->where('chap_index', '<=', $chapter_index)->orderBy('chap_index')->limit(3)->get());
                $temp['chapters'] = array();
                foreach ($chapters as $chapter) {
                    $chap['name'] = $chapter->name;
                    $chap['chap_index'] = $chapter->chap_index;
                    array_push($temp['chapters'], $chap);
                }
                $temp['completed'] = Chapter::where('module_id', $module['id'])
                    ->where('chap_index', '<', $chapter_index)
                    ->count();
            } else if (intval($module_id) > intval($module['id'])) {
                $temp['state'] = 'finished';
                $temp['completed'] = $temp['chap_count'];
            } else {
                $temp['state'] = 'lock';
                $temp['completed'] = 0;
            }
            array_push($result, $temp);
        }
        return json_encode($result);
    }

    public function mindset(Request $request)
    {
        if ($request->wantsJson()) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = Auth::user();
        }
        $currentChapter = Chapter::where('id', $user->chapter_id)->first();;
        $currentModule = Module::where('id', $currentChapter->module_id)->first();
        $isCompleted = true;
        $module = Module::where('name', 'like', 'MINDSET ARENA')->first();
        $chapters = Chapter::with('image', 'video')->where('module_id', $module->id)->orderBy('chap_index')->get();
        foreach ($chapters as $chapter) {
            if($currentModule->mod_index === $module->mod_index) {
                if ($currentChapter->id === $chapter->id) {
                    $chapter->isCompleted = $isCompleted;
                    $isCompleted = false;
                } else {
                    $chapter->isCompleted = $isCompleted;
                }
            }else if($currentModule->mod_index > $module->mod_index) {
                $chapter->isCompleted = true;
            }else if($currentModule->mod_index < $module->mod_index) {
                $chapter->isCompleted = false;
            }
        }
        $video = "b91ad04047f511e99edfb54fabb4e0c8";
        $story_video = "cf62fe0047f511e99d82976a4f943639";
        $story_image = "84fcff6010d311e99dbeadb4286de637.jpg";
        $image = "8a142ac0457c11e9bb6e19aaea23b59e.jpg";
        if ($request->wantsJson()) {
             $result = array();
             $result['chapters'] = $chapters;
             $result['module'] = $module;
             $result['image'] = $image;
             $result['video'] = $video;
             $result['story_video'] = $story_video;
             $result['story_image'] = $story_image;
             return json_encode($result);
        }
        return view('user.chapter')->with([
            'chapters' => $chapters,
            'module' => $module,
            'image' => $image,
            'video' => $video,
            'story_video' => $story_video,
            'story_image' => $story_image,
        ]);
    }

    public function spending_pattern(Request $request)
    {
        if ($request->wantsJson()) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = Auth::user();
        }
        $currentChapter = Chapter::where('id', $user->chapter_id)->first();;
        $currentModule = Module::where('id', $currentChapter->module_id)->first();
        $isCompleted = true;
        $module = Module::where('name', 'like', 'SPENDING PATTERN')->first();
        $chapters = Chapter::with('image', 'video')->where('module_id', $module->id)->orderBy('chap_index')->get();
        foreach ($chapters as $chapter) {
            if($currentModule->mod_index === $module->mod_index) {
                if ($currentChapter->id === $chapter->id) {
                    $chapter->isCompleted = $isCompleted;
                    $isCompleted = false;
                } else {
                    $chapter->isCompleted = $isCompleted;
                }
            }else if($currentModule->mod_index > $module->mod_index) {
                $chapter->isCompleted = true;
            }else if($currentModule->mod_index < $module->mod_index) {
                $chapter->isCompleted = false;
            }
        }
        $video = "f5b4588047f511e99fa5872662a34903";
        $story_image = "876bdb4010d311e9b23493a145de20e5.jpg";
        $story_video = "02972ce047f611e9a10e39ab26590dc2";
        $image = "8e554500457c11e9bad75fc8a6b4373c.jpg";
        if ($request->wantsJson()) {
             $result = array();
             $result['chapters'] = $chapters;
             $result['module'] = $module;
             $result['image'] = $image;
             $result['video'] = $video;
             $result['story_video'] = $story_video;
             $result['story_image'] = $story_image;
             return json_encode($result);
        }
        if ($request->wantsJson()) {
             $result = array();
             $result['chapters'] = $chapters;
             $result['module'] = $modules;
             $result['image'] = $image;
             $result['video'] = $video;
             $result['story_image'] = $story_image;
             $result['story_video'] = $story_video;
             return json_encode($result);
        }
        return view('user.chapter')->with([
            'chapters' => $chapters,
            'module' => $module,
            "image" => $image,
            'video' => $video,
            'story_image' => $story_image,
            'story_video' => $story_video,
        ]);
    }

    public function passive_income(Request $request)
    {
        if ($request->wantsJson()) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = Auth::user();
        }
        $currentChapter = Chapter::where('id', $user->chapter_id)->first();;
        $currentModule = Module::where('id', $currentChapter->module_id)->first();
        $isCompleted = true;
        $module = Module::where('name', 'like', 'PASSIVE INCOME')->first();
        $chapters = Chapter::with('image', 'video')->where('module_id', $module->id)->orderBy('chap_index')->get();
        foreach ($chapters as $chapter) {
            if($currentModule->mod_index === $module->mod_index) {
                if ($currentChapter->id === $chapter->id) {
                    $chapter->isCompleted = $isCompleted;
                    $isCompleted = false;
                } else {
                    $chapter->isCompleted = $isCompleted;
                }
            }else if($currentModule->mod_index > $module->mod_index) {
                $chapter->isCompleted = true;
            }else if($currentModule->mod_index < $module->mod_index) {
                $chapter->isCompleted = false;
            }
        }
        $video = "eaa255f047f611e994722d31a3f1a067";
        $story_image = "8a0a98f010d311e9a10f3b132589fcc1.jpg";
        $story_video = "f3dd01c047f611e9bab26d60a42d1176";
        $image = "932a3a70457c11e9a8d6f9295c577b92.jpg";
        if ($request->wantsJson()) {
             $result = array();
             $result['chapters'] = $chapters;
             $result['module'] = $module;
             $result['image'] = $image;
             $result['video'] = $video;
             $result['story_image'] = $story_image;
             $result['story_video'] = $story_video;
             return json_encode($result);
        }
        return view('user.chapter')->with([
            'chapters' => $chapters,
            'module' => $module,
            'image' => $image,
            'video' => $video,
            'story_image' => $story_image,
            'story_video' => $story_video,
        ]);
    }

    public function networking(Request $request)
    {
        if ($request->wantsJson()) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = Auth::user();
        }
        $currentChapter = Chapter::where('id', $user->chapter_id)->first();;
        $currentModule = Module::where('id', $currentChapter->module_id)->first();
        $isCompleted = true;
        $module = Module::where('name', 'like', 'NETWORKING')->first();
        $chapters = Chapter::with('image', 'video')->where('module_id', $module->id)->orderBy('chap_index')->get();
        foreach ($chapters as $chapter) {
            if($currentModule->mod_index === $module->mod_index) {
                if ($currentChapter->id === $chapter->id) {
                    $chapter->isCompleted = $isCompleted;
                    $isCompleted = false;
                } else {
                    $chapter->isCompleted = $isCompleted;
                }
            }else if($currentModule->mod_index > $module->mod_index) {
                $chapter->isCompleted = true;
            }else if($currentModule->mod_index < $module->mod_index) {
                $chapter->isCompleted = false;
            }
        }
        $video = "220a03f047f711e98d765b628307eeea";
        $story_image = "8e03846010d311e9b214655fd71bd645.jpg";
        $story_video = "6bb9caf047f711e996f765655e132e8e";
        $conclusion_video = "76c475c0480f11e989b8f902acff0f02";
        $conclusion_image = "b8c56980457c11e9b3c36ddc59de6002.jpg";
        $image = "97877bc0457c11e995f181823e67af35.jpg";
        if ($request->wantsJson()) {
             $result = array();
             $result['chapters'] = $chapters;
             $result['module'] = $module;
             $result['image'] = $image;
             $result['video'] = $video;
             $result['story_image'] = $story_image;
             $result['story_video'] = $story_video;
             $result['conclusion_video'] = $conclusion_video;
             $result['conclusion_image'] = $conclusion_image;
             return json_encode($result);
        }
        return view('user.chapter')->with([
            'chapters' => $chapters,
            'module' => $module,
            'image' => $image,
            'video' => $video,
            'conclusion_video' => $conclusion_video,
            'conclusion_image' => $conclusion_image,
            'story_image' => $story_image,
            'story_video' => $story_video,
        ]);
    }
}
