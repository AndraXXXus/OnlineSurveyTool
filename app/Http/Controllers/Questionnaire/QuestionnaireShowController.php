<?php

namespace App\Http\Controllers\Questionnaire;

use Illuminate\Http\Request;
use App\Models\Survey\User;
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

class QuestionnaireShowController extends Controller
{

    public function startQuestionnaire(string $questionnaire_id){
        $questionnaire = Survey::whereNull('deleted_at')->whereNotNull('questionnaire_id')->where('questionnaire_id',$questionnaire_id)->first();

        if($questionnaire === null){
            return abort(419);
        }

        $survey = $questionnaire;

        if($survey->questionnaire_id === null){
            return abort(419);
        }

        $question = $survey->questions()->orderBy('questions.question_position')->first();

        if($question === null){
            return abort(404,'No questions yet');
        }

        Session::put('responder_id', Uuid::uuid4());
        Session::put('question', $question);
        Session::put('survey', $survey);

        $last = isset($question) ? $question->nextQuestion() === null : true;

        return view('questionnaires.startend')->with(['survey' => $survey, 'question' => $question, "last" => $last]);
    }

    public function endQuestionnaire(string $questionnaire_id){
        $questionnaire = Survey::whereNull('deleted_at')->whereNotNull('questionnaire_id')->where('questionnaire_id',$questionnaire_id)->first();

        if($questionnaire === null){
            return abort(419);
        }

        $survey = Session::get('survey');

        Session::forget('responder_id');
        Session::forget('question');
        Session::forget('survey');
        return view('questionnaires.startend')->with(['survey' => $survey, "finished" => true]);
    }

    public function show(string $questionnaire_id){
        $questionnaire = Survey::whereNull('deleted_at')->whereNotNull('questionnaire_id')->where('questionnaire_id',$questionnaire_id)->first();

        if($questionnaire === null){
            return abort(419);
        }

        $responder_id = Session::get('responder_id');
        $question = Session::get('question');
        $survey = Session::get('survey');

        if($survey === null || $responder_id === null || $question === null){
            return abort(419);
        }

        if($question === null ||  $responder_id === null){
            Session::put('something_went_wrong');
            return redirect()->route('questionnaires.end',['survey_questionnaire_id' => $questionnaire_id]);
        }
        $choices = $question->choices->sortBy('choice_position');

        $last = isset($question) ? $question->nextQuestion() === null : true;

        $previous_answers_choice_ids = Answer::where('survey_id',$survey->id)->where('responder_id',$responder_id)->where('question_id',$question->id)->get()->pluck('choice_id');
        if ($previous_answers_choice_ids===null){
            $previous_answers_choice_ids = [];
        }
        $previous_answers_choice_ids = $previous_answers_choice_ids->toArray();

        $previous_answers_choice_texts = Answer::where('survey_id',$survey->id)->where('responder_id',$responder_id)->where('question_id',$question->id)->get(['choice_id','answer_text']);
        $previous_answers_choice_texts_json = json_decode($previous_answers_choice_texts, true);

        $previous_answers_choice_texts = array_combine(array_column($previous_answers_choice_texts_json, 'choice_id'), array_column($previous_answers_choice_texts_json, 'answer_text'));


        if ($previous_answers_choice_ids===null){
            $previous_answers_choice_ids = [];
        }

        Session::put('question_started_at',  now());

        return view('questionnaires.show')->with(['survey' => $survey, 'question'=>$question, 'choices'=>$choices, "last" => $last, 'previous_answers_choice_ids'=>$previous_answers_choice_ids, "previous_answers_choice_texts" => $previous_answers_choice_texts]);
    }

    public function show_previous_question(string $questionnaire_id){
        $questionnaire = Survey::whereNull('deleted_at')->whereNotNull('questionnaire_id')->where('questionnaire_id',$questionnaire_id)->first();

        if($questionnaire === null){
            return abort(419);
        }

        $question = Session::get('question');
        $previous_question = $question->previousQuestion();

        if($question === null){
            return abort(419);
        }

        if($previous_question!==null){
            $question = Session::put('question', $previous_question);
        }
        return redirect()->route('questionnaires.show',['survey_questionnaire_id' => $questionnaire_id]);
    }

}
