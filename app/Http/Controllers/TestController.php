<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Module;
use App\Question;
use App\Test;
use App\User;
use App\Http\Resources\Question as QuestionResource;
use Auth;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testAvg($user_id)
    {
        $result['test_avg'] = number_format(Test::where('user_id', $user_id)->avg('result'), 2);
        return json_encode($result);
    }
    public function testRecords(Request $request, $user_id = null)
    {
        if($user_id){
            $records = Test::where('user_id', $user_id)->latest()->limit(10)->get();
        }else {
            $records = Test::where('user_id', $request->user_id)->latest()->limit(10)->get();
        }
        
        $data = array();
        $lable = array();
        foreach ($records as $record) {
            array_push($data, number_format($record->result, 2));
            array_push($lable, date_format($record->created_at, "d/m/Y H:i"));
        }
        $result['data'] = $data;
        $result['lable'] = $lable;
        return json_encode($result);
    }

    public function checkAnswer(Request $request)
    {
        if($request->wantsJson()) {
            $user = User::where('api_key', $request->api_key)->first();
        } else { 
            $user = Auth::user();
        }
        $chapter_id = $user->chapter_id;
        $questions = Question::where("chapter_id", $chapter_id)->get();
        $counter = 0;
        foreach ($questions as $question) {
            if ($question->answer === $request->get('optradio-' . $question->id)) {
                $counter++;
            }
            $question->user_answer = $request->get('optradio-' . $question->id);
        }
        if (count($questions) > 0) {
            $percentage = $counter / count($questions) * 100;
            $test = new Test;
            $test->user_id = $user->id;
            $test->chapter_id = $chapter_id;
            $test->result = number_format($percentage, 2);
            $test->save();
        } else {
            $percentage = 100;
        }
        if ($percentage >= 100) {
            $user = User::findOrFail($user->id);
            $chapter = Chapter::findOrFail($chapter_id);
            $chapter_id = $user->chapter_id;
            $module_id = $chapter->module_id;
            $chapter = Chapter::where('module_id', $module_id)->where('chap_index', '>', $chapter->chap_index)->orderBy('chap_index')->first();
            if ($chapter === null) {
                $module = Module::where('id', $module_id)->first();
                $module = Module::where('mod_index', '>', $module->mod_index)->orderBy('mod_index')->first();
                if ($module != null) {
                    $chapter = Chapter::where('module_id', $module->id)->orderBy('chap_index')->first();
                    if ($chapter != null) {
                        $chapter_id = $chapter->id;
                    }
                } else {
                    $module = Module::where('id', $module_id)->first();
                    $chapter_id = Chapter::where('module_id', $module->id)->orderBy('chap_index', 'desc')->first()->id;
                    $user->isModuleCompleted = 1;
                    $user->save();
                }
            } else {
                $chapter_id = $chapter->id;
            }
            $user->chapter_id = $chapter_id;
            $user->save();
            if ($request->wantsJson()) {
                return "1";
            } else {
                return redirect('/test/result')->with([
                    'result' => 'pass',
                    'questions' => QuestionResource::collection($questions)
                ]);
            }
        } else {
            if ($request->wantsJson()) {
                return "0";
            } else {
                return redirect('/test/result')->with([
                    'result' => 'fail',
                    'questions' => QuestionResource::collection($questions)
                ]);
            }
        }
    }
}
