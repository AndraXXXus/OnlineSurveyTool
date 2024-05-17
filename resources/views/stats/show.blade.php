@extends('layouts.app')
@section('title', 'Stats')

@section('content')

<div class="container">

    <h1 class="font-bold my-4 text-4xl text-center"> Stats </h1>
    <div class="d-flex flex-wrap items-center  justify-content-between gap-2 p-3">
        <a href="{{ route('stats.download_csv',  ['survey' => $survey]) }}" class="btn btn-primary"></i><i class="fa-solid fa-file-arrow-down"></i> Export as CSV </a>
        <a href="{{ route('stats.clustering',  ['survey' => $survey]) }}" class="btn btn-rebeccapurple">Clustering Answers</a>
    </div>
    <hr>

    <div class="d-flex flex-wrap items-center  justify-content-center gap-2 p-3">


                @include('stats.partials.charts.qfilledbarchart',
                [
                    'labels' => $answersCount->pluck('question_position'),
                    'data' => $answersCount->pluck('responder_count'),
                    'id' =>'qfilled',
                    "title" => "Drop off"
                ])


    {{-- </div>
    <div class="d-flex flex-wrap items-center justify-content-center gap-2 p-3"> --}}


                @include('stats.partials.charts.qfilledbarchart',
                [
                    'labels' => $data_occurence->pluck('date'),
                    'data' => $data_occurence->pluck('distinct_responders'),
                    'id' =>'dayByday',
                    "title" => "Daily distribution"
                ])





                @php
                    $obj = json_decode($data_duration, true);
                    ksort($obj);
                    $keys = (array) array_keys($obj);
                    $values = (array) array_values($obj);

                @endphp
                @include('stats.partials.charts.qfilledbarchart',
                [
                    'labels' => $keys,
                    'data' => $values,
                    'id' =>'duration',
                    "title" => "Fill Duration distribution (in minutes)"
                ])


    </div>

    <div class="d-flex flex-wrap items-center justify-content-center gap-2 p-3">

                @php
                    $questions_count = $survey->questions()->count();
                @endphp

                @forelse ($answer_question_ids as $answer_question_id)
                    <div class="card">
                        <div class="card-body">
                            @php
                                $answerTextArray = [];
                                $countArray = [];
                                $answerArray = $answerTextCountPerQuestion[$answer_question_id];

                                foreach ($answerArray as $answer) {
                                    $answerText = $answer['answer_text'];
                                    $count = $answer['count'];

                                    $answerTextArray[] = $answerText;
                                    $countArray[] = $count;
                                }
                                $question = $survey->questions()->findOrFail($answer_question_id);
                                $question_text = $question->question_text;
                                $question_position = $question->question_position;
                                $question_type = $question->question_type;
                            @endphp

                            @include('stats.partials.charts.piechart',
                            ['labels' => $answerTextArray,
                            'data' => $countArray,
                            'question_text'=>$question_text ,
                            'extra_text'=>  $question_type . ", #" . $question_position ."/" . $questions_count,
                            'question_id'=>$answer_question_id,
                            "question_position"=>$question_position,
                            "question_type"=>$question_type,
                            ])

                        </div>

                    </div>
                @empty
                    No responders yet!
                @endforelse


    </div>


</div>
@endsection
