<?php

namespace App\Http\Controllers\Survey;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Survey\Survey;
use App\Models\Survey\Question;
use App\Models\Survey\Choice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CreateOrUpdateChoiceRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ChoiceController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Survey $survey, Question $question)
    {
        if($question->survey != $survey){
            return abort(403, "Survey and question do not match");
        }
        $this->authorize('surveyAndUserMatch', $survey);

        $choices = $question->choices->whereNull('deleted_at')->sortBy('choice_position');
        return view('surveys.choices.index')->with(['survey' => $survey,'question'=>$question ,'choices' => $choices]);
    }

    public function archive(Question $question)
    {
        $survey = $question->survey;
        $this->authorize('surveyAndUserMatch', $survey);
        $choices = $question->choices()->onlyTrashed()->orderBy('deleted_at', 'DESC')->get();

        $archive = true;

        return view('surveys.choices.index')->with(['survey' => $survey,'question'=>$question ,'choices' => $choices , 'archive' => $archive]);
    }

    public function create(Question $question)
    {
        $this->authorize('surveyAndUserMatch', $question->survey);

        return view('surveys.choices.create')->with(['question' => $question]);
    }

    public function store(CreateOrUpdateChoiceRequest $request, Question $question)
    {
        $survey = $question->survey;

        $this->authorize('surveyAndUserMatch', $survey);

        $data['choice_text'] = $request->input('choice_text');

        $data['deleted_at'] = null;
        $data['question_id'] = $question->id;

        $data['choice_position'] = $question->choices->max('choice_position')+1;

        $choice = Choice::create($data);

        Session::flash('choice_created');

        return redirect()->route('survey.question.index', ['survey' => $survey, 'question' => $question]);
    }

    public function edit(Choice $choice)
    {
        $question = $choice->question;
        $survey = $question->survey;

        $this->authorize('surveyAndUserMatch', $survey);

        return view('surveys.choices.create')->with(['choice' => $choice, 'question' => $question, 'survey'  =>  $survey]);
    }


    public function update(CreateOrUpdateChoiceRequest $request, Choice $choice)
    {
        $question = $choice->question;
        $survey = $question->survey;

        $this->authorize('surveyAndUserMatch', $survey);

        // if ( $request->delete_image === true ){$data['image'] = null;}
        // else{ $data['image'] = $survey->image; }

        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $data['image'] = $file->hashName();
        //     Storage::disk('images')->put('/' . $data['image'], $file->get());
        // }

        $choice->choice_text = $request->input('choice_text');
        $choice->deleted_at = null;
        $choice->question_id = $question->id;

        $choice->save();

        Session::flash('choice_updated');

        return redirect()->route('survey.question.index', ['survey' => $survey, 'question' => $question]);
    }

    public function destroy(Choice $choice)
    {
        $question = $choice->question;
        $survey = $question->survey;

        $this->authorize('surveyAndUserMatch', $survey);


        //$this->authorize('delete', $survey);

        $deleted = $choice->delete();
        if (!$deleted) {
            return abort(500);
        }

        $current_position =  $choice->choice_position;
        $choice->choice_position = null;
        $choice->save();

        $question->choices()
        ->where('choice_position', ">" ,$current_position)
        ->orderBy('choice_position', 'ASC')->get()
        ->each(function ($choice) {
            $choice->decrement('choice_position');
            $choice->save();
        });



        Session::flash('choice_deleted');
        //return redirect()->route('choices.show',['question' => $question]);
        return redirect()->route('survey.question.index', ['survey' => $survey, 'question' => $question]);
    }



    public function restore(String $choice){
        $choice = Choice::onlyTrashed()->findOrFail($choice);
        $question = $choice->question;
        $survey = $question->survey;

        $this->authorize('surveyAndUserMatch', $survey);


        $choice->restore();
        $choice->choice_position = $question->choices()->count();
        $choice->save();

        Session::flash('chioce_restored');

        return redirect()->route('survey.question.index', ['survey' => $survey, 'question' => $question]);
    }

    public function movechoice(Choice $choice, String $up_or_down){

        $this->authorize('surveyAndUserMatch', $choice->question->survey);

        $modification = 0;
        if($up_or_down === "up"){
            $modification = -1;
        }
        elseif($up_or_down === "down"){
            $modification = 1;
        }

        $question = $choice->question;
        $survey = $question->survey;
        $user = $survey->user;

        $canbemoved = false;
        if (($up_or_down === "up") && ($choice->positionCanDecrease()===true)){
            $canbemoved = true;
        }
        elseif(($up_or_down === "down") && ($choice->positionCanIncrease()===true)){
            $canbemoved = true;
        }

        if($canbemoved===true){
            $current_position = $choice->choice_position;
            $choice_to_be_pushed = $question->choices()->where('choice_position',$current_position+$modification)->first();

            $choice->choice_position = $current_position+$modification;
            $choice_to_be_pushed->choice_position = 0;
            $choice_to_be_pushed->save();
            $choice->save();

            $choice_to_be_pushed->choice_position = $current_position;
            $choice_to_be_pushed->save();
        }

        return redirect()->route('survey.question.index', ['survey' => $survey, 'question' => $question]);
    }

    public function moveup(Choice $choice){
        return ChoiceController::movechoice($choice,"up");
    }

    public function movedown(Choice $choice){
        return ChoiceController::movechoice($choice,"down");
    }

    public function forcedelete(String $choice)
    {
        $choice = Choice::onlyTrashed()->findOrFail($choice);
        $this->authorize('surveyAndUserMatch', $choice->question->survey);
        $question = $choice->question;
        $survey = $question->survey;

        $deleted = $choice->forceDelete();
        if (!$deleted) {
            return abort(500);
        }

        Session::flash('choice_forcedeleted');
        return redirect()->route('survey.question.index', ['survey' => $survey, 'question' => $question]);
    }

}

