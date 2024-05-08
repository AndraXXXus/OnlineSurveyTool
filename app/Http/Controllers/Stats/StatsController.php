<?php

namespace App\Http\Controllers\Stats;

use Illuminate\Http\Request;
use App\Models\Survey\User;
use App\Models\Survey\Survey;
use App\Models\Survey\Question;
use App\Models\Survey\Choice;
use App\Models\Survey\Answer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Boolean;
use Ramsey\Uuid\Uuid;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class StatsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Survey $survey){

        $this->authorize('questionnaireTeamMembersAndUserMatch',$survey);

        $answersCount = Answer::where('answers.survey_id',$survey->id)->join('questions', 'answers.question_id', '=', 'questions.id')
        ->select('answers.question_id','questions.question_position', DB::raw('count(distinct answers.responder_id) as responder_count'))
        ->groupBy('answers.question_id','questions.question_position')
        ->orderBy('questions.question_position', 'asc')
        ->get();
        // dd($answersCount->pluck('responder_count'));
        $answerTextCountPerQuestion = Answer::where('answers.survey_id',$survey->id)
        ->join('questions', 'answers.question_id', '=', 'questions.id')
        ->select('answers.question_id', 'answers.answer_text','questions.question_position', DB::raw('COUNT(*) as count'))
        ->groupBy('answers.question_id', 'answers.answer_text','questions.question_position')
        ->orderBy('questions.question_position', 'asc')->get();

        $counts = [];

        foreach ($answerTextCountPerQuestion as $answer) {
            $questionId = $answer->question_id;
            $answerText = $answer->answer_text;
            $count = $answer->count;

            if (!isset($counts[$questionId])) {
                $counts[$questionId] = [];
            }

            $counts[$questionId][] = [
                'answer_text' => $answerText,
                'count' => $count,
            ];
        }

        $answerTextCountPerQuestion = $counts;

        $answer_question_ids = Answer::where('answers.survey_id',$survey->id)->join('questions', 'answers.question_id', '=', 'questions.id')->orderBy('questions.question_position', 'asc')->pluck('answers.question_id')->unique();


        $data_occurence = Answer::where('answers.survey_id',$survey->id)->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(DISTINCT responder_id) as distinct_responders'))
        ->groupBy('date')
        ->get();

        $data_duration='[]';

        if(env('DB_CONNECTION') == 'sqlite'){
            $data_duration = Answer::where('answers.survey_id',$survey->id)
            ->select('responder_id',DB::raw('(strftime("%s", MAX(updated_at)) - strftime("%s", MIN(question_started_at))) AS time_difference_minutes'))
            ->groupBy('responder_id')
            ->pluck('time_difference_minutes')->countBy(function($value) {
                return $value / 60;
            });
        }

        if(env('DB_CONNECTION') == 'pgsql'){
            $data_duration = Answer::where('answers.survey_id',$survey->id)
            ->select('responder_id', DB::raw('EXTRACT(EPOCH FROM MAX(updated_at)) - EXTRACT(EPOCH FROM MIN(question_started_at)) AS time_difference_minutes'))
            ->groupBy('responder_id')
            ->pluck('time_difference_minutes')->countBy(function($value) {
                return $value/60;
            });
        }



        return view('stats.show')->with(['survey' => $survey, 'answersCount'=>$answersCount,'answer_question_ids'=>$answer_question_ids,'answerTextCountPerQuestion'=>$answerTextCountPerQuestion,
        'data_occurence'=>$data_occurence,
        'data_duration'=>$data_duration]);
    }

    public function clustering(Survey $survey){
        $this->authorize('questionnaireTeamMembersAndUserMatch',$survey);

        $answers = Answer::where('answers.survey_id', $survey->id)
        ->join('questions', 'answers.question_id', '=', 'questions.id')
        ->join('surveys', 'answers.survey_id', '=', 'surveys.id')
        ->get();

        $question_id_X_answer_text = [];
        foreach ($answers as $item) {
            $answer_text = $item['answer_text'];
            $question_id = $item['question_id'];
            $question_id_X_answer_text[] = $question_id . " X " . $answer_text;
        }
        $question_id_X_answer_text = array_unique($question_id_X_answer_text);
        $question_id_X_answer_text = array_combine(range(0, count($question_id_X_answer_text)-1), array_values($question_id_X_answer_text));

        $responderIds = $answers->pluck('responder_id')->unique()->toArray();
        $responderIds = array_combine(range(0, count($responderIds)-1), array_values($responderIds));

        $matrix = [];

        $zero_array = array_fill(0, sizeof($question_id_X_answer_text),0);

        foreach ($responderIds as $responderId) {
            $matrix[] = $zero_array;
        }

        foreach ($answers as $answer) {
            $responderId = $answer->responder_id;
            $questionId = $answer->question_id;
            $answerText = $answer->answer_text;
            $questionId_answerText_index = array_search($questionId . ' X ' . $answerText, $question_id_X_answer_text);
            $responderId_index = array_search($responderId, $responderIds);

            $matrix[$responderId_index][$questionId_answerText_index] = 1;
        }


        $matrix_tokenized = [];
        foreach ($matrix as $row) {
            $matrix_tokenized[] =  $row;
        }

        return view('stats.clustering')->with(['matrix' => $matrix_tokenized, 'responderIds' => $responderIds]);
    }

    public function download_csv(Survey $survey){
        $this->authorize('questionnaireTeamMembersAndUserMatch',$survey);

        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=export_answers_' . $survey->id . '.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $answers = Answer::where('answers.survey_id', $survey->id)
        ->join('questions', 'answers.question_id', '=', 'questions.id')
        ->join('surveys', 'answers.survey_id', '=', 'surveys.id')
        ->get();

        $answers_list =  $answers->toArray();

        array_unshift($answers_list, array_keys($answers_list[0]));

        $callback = function() use ($answers_list){
            $FH = fopen('php://output', 'w');
            foreach ($answers_list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

}

