<?php

namespace App\Http\Controllers\Survey;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Survey\Survey;
use App\Models\Survey\Question;
use App\Models\Survey\Choice;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CreateOrUpdateQuestionRequest;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Survey $survey)
    {
        $user = User::findOrFail(Auth::id());

        $this->authorize('surveyAndUserMatch', $survey);

        $questions = $survey->questions->sortBy('question_position');

        return view('surveys.questions.show')->with(['survey' => $survey,'questions'=>$questions]);
    }

    public function archive(Survey $survey)
    {
        $user = User::findOrFail(Auth::id());

        $this->authorize('surveyAndUserMatch', $survey);

        $archive=true;
        $questions = $survey->questions()->onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('surveys.questions.show')->with(['survey' => $survey,'questions'=>$questions,'archive'=>$archive]);

    }

    public function create(Survey $survey)
    {
        $user = User::findOrFail(Auth::id());

        $this->authorize('surveyAndUserMatch', $survey);

        $allowedQuestionTypes = Question::getAllowedQuestionTypes();
        return view('surveys.questions.create')->with(['survey' => $survey, 'allowedQuestionTypes' => $allowedQuestionTypes]);
    }

    public function edit(Question $question)
    {
        $survey = $question->survey;
        $user = $survey->user;
        $user = User::findOrFail(Auth::id());
        $this->authorize('surveyAndUserMatch', $survey);


        $allowedQuestionTypes = Question::getAllowedQuestionTypes();

        return view('surveys.questions.create')->with(['question' => $question, 'allowedQuestionTypes' => $allowedQuestionTypes, 'survey' => $survey]);
    }

    public function store(CreateOrUpdateQuestionRequest $request, Survey $survey)
    {
        $user = $survey->user;
        $user = User::findOrFail(Auth::id());
        $this->authorize('surveyAndUserMatch', $survey);

        $data['question_text'] = $request->input('question_text');
        $data['question_type'] = $request->input('question_type');
        $data['question_required'] = $request->input('question_required') === "1" ? true : false;

        $data['deleted_at'] = null;
        $data['survey_id'] = $survey->id;
        $data['question_position'] = $survey->questions()->get()->count()+1;

        $data['prefer_video'] = isset($request->imageOrVideoSwitch) ? ($request->input('imageOrVideoSwitch')==="on" ? true : false) : false;
        $data['cover_image_path'] = null;
        $data['youtube_id']=null;

        $question = Question::create($data);

        if($question->prefer_video){
            $question->youtube_id = $request->input('youtube_id');
            if( $question->youtube_id !=null ){;
                $question->video_no_controlls = $data['question_required'];
                $question->save();
            }
        }else{
            if($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $cover_image_path = $question->id . '.'.$file->getClientOriginalExtension();
                Storage::disk('public')->put(
                    $cover_image_path,$file->get()
                );
                $question->cover_image_path = $cover_image_path;
                $question->save();
            }
        }

        Session::flash('question_created', $question);
        return redirect()->route('survey.question.show', ['survey' => $survey, 'question' => $question]);
    }

    public function update(CreateOrUpdateQuestionRequest $request, Question $question)
    {
        $survey = $question->survey;
        $user = $survey->user;

        $user = User::findOrFail(Auth::id());
        $this->authorize('surveyAndUserMatch', $survey);


        $question->question_text = $request->input('question_text');
        $question->question_type = $request->input('question_type');
        $question->question_required = $request->input('question_required') === "1" ? true : false;
        $question->deleted_at = null;

        $question->prefer_video = isset($request->imageOrVideoSwitch) ? ($request->input('imageOrVideoSwitch')==="on" ? true : false) : false;

        if($question->prefer_video){
            if ($question->cover_image_path!=null){
                Storage::disk('public')->delete($question->cover_image_path);
            }
            $question->cover_image_path = null;
            $question->video_no_controlls = false;

            $question->youtube_id = $request->input('youtube_id');

            if( $question->youtube_id !=null ){
                $question->video_no_controlls = $question->question_required;
            }
        }else{
            $question->youtube_id = null;
            $question->video_no_controlls = false;

            $old_cover_image_path = $question->cover_image_path;

            if($request->hasFile('cover_image')) {
                if($old_cover_image_path != null) {
                    Storage::disk('public')->delete($old_cover_image_path);
                }
                $file = $request->file('cover_image');
                // $cover_image_path = time();
                $question->cover_image_path = $question->id . '.'.$file->getClientOriginalExtension();
                Storage::disk('public')->put(
                    $question->cover_image_path ,$file->get()
                );
            }
            elseif ( $request->input('remove_cover_image') === "1" ){
                $question->cover_image_path = null;
                Storage::disk('public')->delete($old_cover_image_path);
            }
        }
        $question->save();

        Session::flash('question_updated', $question);


        return redirect()->route('survey.question.show', ['survey' => $survey, 'question' => $question]);
    }

    public function movequestion(Question $question, String $up_or_down){

        $survey = $question->survey;
        $this->authorize('surveyAndUserMatch', $question->survey);

        $modification = 0;
        if($up_or_down === "up"){
            $modification = -1;
        }
        elseif($up_or_down === "down"){
            $modification = 1;
        }

        $canbemoved = false;
        if (($up_or_down === "up") && ($question->positionCanDecrease()===true)){
            $canbemoved = true;
        }
        elseif(($up_or_down === "down") && ($question->positionCanIncrease()===true)){
            $canbemoved = true;
        }

        if($canbemoved===true){
            $current_position = $question->question_position;
            $question_to_be_pushed = $survey->questions()->where('question_position',$current_position+$modification)->first();

            $question->question_position = $current_position+$modification;
            $question_to_be_pushed->question_position = 0;
            $question_to_be_pushed->save();
            $question->save();

            $question_to_be_pushed->question_position = $current_position;
            $question_to_be_pushed->save();
        }

        return redirect()->route('questions.show', ['survey' => $survey]);
    }

    public function moveup(Question $question){
        return QuestionController::movequestion($question,"up");
        //return redirect()->route('questions.movequestion',['question' => $question, "up_or_down" => "up"]);
    }

    public function movedown(Question $question){
        return QuestionController::movequestion($question,"down");
        //return redirect()->route('questions.movequestion',['question' => $question, "up_or_down" => "down"]);
    }

    public function destroy(Question $question)
    {
        $survey = $question->survey;

        $this->authorize('surveyAndUserMatch', $question->survey);

        //$this->authorize('delete', $survey);

        $deleted = $question->delete();
        if (!$deleted) {
            return abort(500);
        }

        $current_position =  $question->question_position;
        $question->question_position = null;
        $question->save();

        $survey->questions()
        ->where('question_position', ">" ,$current_position)
        ->orderBy('question_position', 'ASC')->get()
        ->each(function ($question) {
            $question->decrement('question_position');
            $question->save();
        });

        Session::flash('question_deleted', $question);
        return redirect()->route('questions.show',['survey' => $survey]);
    }



    public function restore(String $question){
        $question = Question::onlyTrashed()->findOrFail($question);
        $survey = $question->survey;

        $this->authorize('surveyAndUserMatch', $question->survey);

        $question->restore();

        $question->question_position = $survey->questions()->count()  ;
        $question->save();

        Session::flash('question_restored', $question);

        return redirect()->route('questions.show', ['survey' => $survey]);
    }


    public function forcedelete(String $question)
    {
        $question = Question::withTrashed()->findOrFail($question);
        $survey = $question->survey;

        $this->authorize('surveyAndUserMatch', $question->survey);


        //$this->authorize('delete', $survey);
        $cover_image_path = $question->cover_image_path;
        $deleted = $question->forceDelete();
        if (!$deleted) {
            return abort(500);
        }

        if($cover_image_path != null) {
            Storage::disk('public')->delete($cover_image_path);
        }

        Session::flash('question_forcedeleted', $question);
        return redirect()->route('questions.show',['survey' => $survey]);
    }

    public function clone(Question $question){
        $survey = $question->survey;

        $this->authorize('surveyAndUserMatch', $question->survey);

        $question->deepCopyQuestion($survey);

        return redirect()->route('questions.show',['survey' => $survey]);
    }
}

