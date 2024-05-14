@extends('layouts.guest')
@section('title', 'Fill out the Survey')

@section('content')
<div class="container">
    <div class="d-flex justify-content-center gap-3 ">
        <div class="col p-3 justify-content-center gap-3 text-center">
            @if (Session::has('something_went_wrong'))
                <div class="alert alert-success">
                    Something went wrong!
                </div>
            @endif


            <h1> Welcome to the survey! </h1>
            <h2> Feel free to take it!</h2>
            @isset($survey)
            @include('surveys.questions.partials.cards.card')
            @endisset
            @if(isset($finished) && $finished===true)
            <div class="px-2.5 py-2 flex-1 flex flex-col justify-between">
                <h3 class="text-success">Thank you for participating in our survey!</h3>
            </div>
            @else
            <div class="px-2.5 py-2 flex-1 flex flex-col justify-between">
                @if($survey->questions()->count()>0)
                <h2 class="text-danger">By clicking the "Acknowledge" button below you agree to participate in this survey and understand that your answers will be stored and forfeit any right to them.</h2>

                <a class="btn btn-primary text-center" href="{{ route('questionnaires.show', [ 'survey_questionnaire_id' => $survey->questionnaire_id]) }}" > Acknowledged </a>
                @else
                <h1 style="color:red">
                    This survey has no questions yet!
                </h1>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
