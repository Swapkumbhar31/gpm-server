<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Question;
use App\Test;
use Auth;
use App\Http\Resources\Question as QuestionResource;
use Illuminate\Support\Facades\URL;

class QuestionController extends Controller
{
    public function index($chapter_id)
    {
        $questions = Question::where("chapter_id", $chapter_id)->paginate(5);
        return QuestionResource::collection($questions);
    }
    public function testQuestion($chapter_id)
    {
        $questions = Question::where("chapter_id", $chapter_id)->get();
        return QuestionResource::collection($questions);
    }

    public function show($id)
    {
        $question = Question::findOrFail($id);
        return new QuestionResource($question);
    }

    public function create()
    {
        $chapter_id = explode("/",URL::previous());
        $chapter_id = $chapter_id[count($chapter_id) - 1];
        if(!is_numeric($chapter_id)){
            return redirect(route('modules'));
        }
        return view('admin.question.add')->with('chapter_id', $chapter_id);
    }

    public function store(Request $request)
    {
        $question = $request->isMethod('put') ? Question::findOrFail($request->id) : new Question;
        $question->id = $request->input('id');
        $question->question = $request->input('question');
        $question->option1 = $request->input('option1');
        $question->option2 = $request->input('option2');
        $question->option3 = $request->input('option3');
        $question->option4 = $request->input('option4');
        $question->answer = $request->input('answer');
        $question->chapter_id = $request->input('chapter_id');
        if($question->save()){
            return redirect(route('viewchapter', $question->chapter_id));
        }
    }

    public function update(Request $request)
    {
        $question = Question::findOrFail($request->id);
        $question->question = $request->input('question');
        $question->option1 = $request->input('option1');
        $question->option2 = $request->input('option2');
        $question->option3 = $request->input('option3');
        $question->option4 = $request->input('option4');
        $question->answer = $request->input('answer');
        $question->chapter_id = $request->input('chapter_id');
        if($question->save()){
            return redirect(route('viewchapter', $question->chapter_id));
        }
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        return view('admin.question.edit')->with('question', $question);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        if($question->delete()){
            return redirect(route('viewchapter', $question->chapter_id));
        }
    }

}
