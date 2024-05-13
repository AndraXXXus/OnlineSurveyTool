@extends('layouts.app')
@section('title', isset($question) ? 'Answers for '  .  $question->question_text :'Unnamed Question' )

@section('content')
<div class="container">
    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show">
        @if (Session::has('new_choice_for_open_question_cant_be_made'))
            <div class="alert alert-success">
                Open question can only have one choice!
            </div>
        @endif
        @if (Session::has('open_choice_not_deleted'))
            <div class="alert alert-success">
                Open choice cannot be deleted!
            </div>
        @endif
        @if (Session::has('open_choice_not_edited'))
            <div class="alert alert-success">
                Open choice cannot be edited!
            </div>
        @endif
        @if (Session::has('question_updated'))
            <div class="alert alert-success">
                Question edited successfully!
            </div>
        @endif
        @if (Session::has('question_created'))
            <div class="alert alert-success">
                New question added successfully!
            </div>
        @endif
        @if (Session::has('question_restored'))
            <div class="alert alert-success">
                Question restored successfully!
            </div>
        @endif
        @if (Session::has('choice_updated'))
            <div class="alert alert-success">
                Choice edited successfully!
            </div>
        @endif
        @if (Session::has('choice_created'))
            <div class="alert alert-success">
                Choice successfully created!
            </div>
        @endif
        @if (Session::has('choice_deleted'))
            <div class="alert alert-success">
                Choice successfully softdeleted!
            </div>
        @endif
        @if (Session::has('choice_forcedeleted'))
            <div class="alert alert-success">
                Choice permanently deleted!
            </div>
        @endif
        @if (Session::has('chioce_restored'))
            <div class="alert alert-success">
                Choice successfully restored!
            </div>
        @endif
    </div>
    <div class="row justify-content-between">
        <div class="col p-3">
            <h1>{{ isset($archive) ? 'Archived' : '' }} Answers
            </h1>
        </div>
        <div class="col p-3">
            <div class="d-flex justify-content-end gap-3">
            @if(!isset($archive))
                @include('surveys.choices.partials.buttons.create',['base_route'=>'choices.create'])
                @include('surveys.choices.partials.buttons.to_questions',['base_route'=>'questions.show'])
                @include('surveys.choices.partials.buttons.to_archives',['base_route'=>'choices.archive'])
            @else
                @include('surveys.choices.partials.buttons.to_choices',['base_route'=>'survey.question.show'])
            @endif
        </div>
    </div>

    <hr>

    @if(!isset($archive))

        <div class="d-flex col p-3 justify-content-center gap-3 text-center">

            @include('questionnaires.partials.cards.questioncards',['previous_answers_choice_ids' => [],'previous_answers_choice_texts' => []])

        </div>

    <hr>
    @endif


    <div class="d-flex justify-content-center row mt-3">
        @include('surveys.choices.partials.table.table')


    </div>
</div>
@endsection




