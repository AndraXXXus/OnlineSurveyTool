<?php

namespace App\Http\Controllers\Questionnaire;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Survey\Survey;
use App\Models\Survey\Question;
use App\Models\Survey\Choice;
use App\Models\Survey\Answer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class QuestionnaireStoreController extends Controller
{

    private function storeAnswer(string $survey_id, String $choice_id, String $choice_text){

        $responder_id = Session::get('responder_id');
        $question = Session::get('question');
        $survey = $question->survey;

        if($responder_id === null || $question === null || $survey === null){
            return abort(419);
        }


        $answer = array(
            'responder_id' => $responder_id,
            'survey_id' => $survey_id,
            'choice_id' => $choice_id,
            'question_id' => $question->id,
            'answer_text' => $choice_text,
            'question_started_at' => Session::get('question_started_at'),
        );

        return Answer::Create($answer);
    }

    public function store_open_choices(Request $request, string $survey_id){
        $responder_id = Session::get('responder_id');
        $question = Session::get('question');

        if($responder_id === null || $question === null){
            return abort(419);
        }

        $possible_choice_ids = $question->choices()->pluck('id');

        $question_required = $question->question_required == true  && count($question->choices)>0;

        $validated = $request->validate([
            'open_ids_texts' => ['required', 'array:'.$possible_choice_ids->implode(','), 'size:'.count($possible_choice_ids) ],
            'open_ids_texts.*' => [$question_required ? 'required' : 'nullable','string'],
        ],
            [
                'open_ids_texts.required'  => 'The :attribute is required.',
                "open_ids_texts.size"  => 'Each :attribute must be present and the number of them has to be excaly :size !',
                "open_ids_texts.array" =>'Each element in :attribute must be a valid choice id',
                'open_ids_texts.*.required'  => 'All answers, including :attribute is required.',
                'input_texts.*.string'  => 'The :attribute ha to be a string.',
            ]
        );



        Answer::select()->where('question_id',$question->id)->where('responder_id',$responder_id)->get()->each(function (Answer $answer) {
            $answer->forceDelete();});


        $open_ids_texts = $validated["open_ids_texts"];

        foreach($open_ids_texts as $id=>$value){
            $choice = Choice::findOrFail($id);
            $choice_id = $choice->id;
            $input_text = $value;
            if($value!==null){
                $this->storeAnswer($survey_id, $choice_id, $input_text);
            }
        }
    }

    public function store_multi(Request $request, string $survey_id){
        $responder_id = Session::get('responder_id');
        $question = Session::get('question');

        if($responder_id === null || $question === null){
            return abort(419);
        }

        $possible_choice_ids = $question->choices()->pluck('id');

        $question_required = $question->question_required == true  && count($question->choices)>0;

        $validated = $request->validate([
            'choice_ids' => [$question_required ? 'required' : 'nullable', 'array'],
            "choice_ids.*"  => ['string', 'distinct', Rule::in($possible_choice_ids)],
        ],
            [
                'choice_ids.required'  => 'The :attribute is required.',
                "choice_ids.*.distinct"  => 'Each element in :attribute must be unique.',
                "choice_ids.*.string"  =>'Each element in :attribute must be a string',
                "choice_ids.*.in" => 'Each element in :attribute must be valid choice id',
            ]
        );

        Answer::select()->where('question_id',$question->id)->where('responder_id',$responder_id)->get()->each(function (Answer $answer) {
            $answer->forceDelete();});

        if(count($validated)>0){
            $choice_ids = $validated["choice_ids"];
            foreach($choice_ids as $id){
                $choice = Choice::findOrFail($id);
                $choice_id = $choice->id;
                $choice_text = $choice->choice_text;
                $this->storeAnswer($survey_id, $choice_id, $choice_text);
            }
        }
    }

    public function store_radio_dropdown(Request $request, string $survey_id){
        $responder_id = Session::get('responder_id');
        $question = Session::get('question');

        if($responder_id === null || $question === null){
            return abort(419);
        }

        $possible_choice_ids = $question->choices()->pluck('id');

        $question_required = $question->question_required == true  && count($question->choices)>0;

        $validated = $request->validate([
            'choice_id' => [$question_required ? 'required' : 'nullable', 'string', Rule::in($possible_choice_ids)],
        ],
            [
                'choice_id.required'  => 'An :attribute is required.',
                "choice_id.string"  =>'Each element in :attribute must be a string',
                "choice_id.in" => 'Each element in :attribute must be valid choice id',
            ]
        );

        Answer::select()->where('question_id',$question->id)->where('responder_id',$responder_id)->get()->each(function (Answer $answer) {
            $answer->forceDelete();});


        if($validated!==[]){
            $choice_id = $validated["choice_id"];
            $choice = Choice::findOrFail($choice_id);
            $choice_id = $choice->id;
            $choice_text = $choice->choice_text;
            $this->storeAnswer($survey_id, $choice_id, $choice_text);
        }
    }



    public function store(Request $request, string $questionnaire_id)
    {
        $questionnaire = Survey::whereNull('deleted_at')->whereNotNull('questionnaire_id')->where('questionnaire_id',$questionnaire_id)->first();

        if($questionnaire === null){
            return abort(419);
        }

        $responder_id = Session::get('responder_id');
        $question = Session::get('question');
        $survey = $question->survey;
        if($questionnaire === null){
            return abort(419);
        }
        $survey_id = $survey->id;

        if($responder_id === null || $question === null || $questionnaire->id !== $survey->id){
            return abort(419);
        }

        if ($question->question_type === "open"){
            $this->store_open_choices($request, $survey_id);
        }
        elseif ($question->question_type === "mutiple-choice"){
            $this->store_multi($request, $survey_id);
        }
        elseif ($question->question_type === "radio-button" || $question->question_type === "dropp-down"){
            $this->store_radio_dropdown($request, $survey_id);
        }else{
            Session::put('something_went_wrong');
            return redirect()->route('questionnaires.end',['survey_questionnaire_id' => $questionnaire_id]);
        }

        $next_question = $question->nextQuestion();

        $question = Session::put('question',$next_question);
        Session::save();

        if(is_null($next_question)){
            return redirect()->route('questionnaires.end',['survey_questionnaire_id' => $questionnaire_id]);
        }
        else{
            return redirect()->route('questionnaires.show',['survey_questionnaire_id' => $questionnaire_id]);
        }

    }

}
