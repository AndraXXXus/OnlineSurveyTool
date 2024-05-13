@extends('layouts.app')
@section('title', isset($survey) ? 'Questions for '  .  $survey->survey_title :'Unnamed Survey' )

@section('content')
<div class="container">
    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show">
        @if (Session::has('survey_created'))
            <div class="alert alert-success">
                Survey added successfully!
            </div>
        @endif
        @if (Session::has('survey_updated'))
            <div class="alert alert-success">
                Survey edited successfully!
            </div>
        @endif

        @if (Session::has('survey_restored'))
            <div class="alert alert-success">
                Survey restored successfully!
            </div>
        @endif
        @if (Session::has('question_restored'))
            <div class="alert alert-success">
                Survey restored successfully!
            </div>
        @endif
        @if (Session::has('question_deleted'))
            <div class="alert alert-success">
                Question softdeleted successfully!
            </div>
        @endif
        @if (Session::has('question_forcedeleted'))
            <div class="alert alert-success">
                Question permanently deleted!
            </div>
        @endif
    </div>
    <div class="row justify-content-between ">
        <div class="col p-3">
            <h1>{{ isset($archive) ? 'Archived' : '' }} Questions
            </h1>
        </div>
        <div class="col p-3 ">
            <div class="d-flex justify-content-end gap-3 ">
                @if(!isset($archive))
                    @include('surveys.questions.partials.buttons.create',['base_route'=>'questions.create'])
                    @include('surveys.questions.partials.buttons.to_surveys',['base_route'=>'surveys.index'])
                    @include('surveys.questions.partials.buttons.to_archives',['base_route'=>'questions.archive'])
                @else
                    @include('surveys.questions.partials.buttons.to_questions',['base_route'=>'questions.show'])
                @endif
            </div>
        </div>
    </div>
    <hr>
    @if(!isset($archive))
        <div class="d-flex justify-content-center">
            @include('surveys.questions.partials.cards.card')
        </div>
        <hr>
    @endif






    <div class="d-flex justify-content-center row mt-3">
        @include('surveys.questions.partials.table.table')
    </div>
</div>
@endsection
